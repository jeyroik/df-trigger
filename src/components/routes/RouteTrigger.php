<?php
namespace df\components\routes;

use df\interfaces\anchors\IAnchor;
use df\interfaces\applications\dispatchers\IAppDispatcher;
use df\interfaces\extensions\IExtensionAppSettings;
use df\interfaces\extensions\IExtensionBPS;
use df\interfaces\extensions\IExtensionBPTransition;
use df\interfaces\processes\states\IBPState;
use df\interfaces\processes\transitions\IBPTransition;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\interfaces\repositories\IRepository;
use Psr\Http\Message\ResponseInterface;

/**
 * @method IRepository anchors()
 * @method IRepository bpTransitions()
 */
class RouteTrigger extends JsonDispatcher
{
    public const PARAM__ANCHOR = 'anchor';

    public function execute(): ResponseInterface
    {
        try {
            $anchor     = $this->getAnchor();
            $bpState    = $this->getBps($anchor);
            $dispatcher = $this->getAppDispatcher($bpState);

            $this->validateEvent($dispatcher, $bpState);

            $bpt        = $this->getBpt($bpState);
            $targetBody = $this->getTargetBody($bpt);
            $targetDisp = $this->getTargetAppDispatcher($bpt);
            $this->setResponseData(
                $targetDisp->trigger($bpt->getTarget()->getName(), $targetBody)
            );
        } catch (\Exception $e) {
            $this->setResponseData([], $e->getMessage());
        } 

        return $this->response;
    }

    public function help(): ResponseInterface
    {
        return $this->response;
    }

    protected function getAnchor(): IAnchor
    {
        $anchorId = $this->getRequestParameter(static::PARAM__ANCHOR, '');
        $anchor = $this->anchors()->one([
            IAnchor::FIELD__ID => $anchorId
        ]);

        if (!$anchor) {
            throw new \Exception('Unknown anchor');
        }

        return $anchor;
    }

    protected function getBps(IAnchor $anchor): IBPState
    {
        $bps = $anchor->getBPState();

        if (!$bps) {
            throw new \Exception('Incorrect anchor: unknown bp state');
        }

        return $bps;
    }

    protected function getAppDispatcher(IBPState $bps)
    {
        /**
         * @var IExtensionAppSettings $app
         */
        $app = $bps->getApplication();

        if (!$app) {
            $this->setResponseData([], 'Incorrect BP state: unknown application');
            return $this->response;
        }

        return $app->getDispatcher();
    }

    protected function validateEvent(IAppDispatcher $dispatcher, IBPState $bps): void
    {
        $event = $dispatcher->getEvent($bps->getName(), $this);

        /**
         * @var IExtensionBPS $bps
         */
        if (!$bps->isApplicableEvent($event)) {
            throw new \Exception('Incompatible state data');
        }
    }

    protected function getBpt(IBPState $bps): IBPTransition
    {
        $bpt = $this->bpTransitions()->one([
            IBPTransition::FIELD__SOURCE_ID => $bps->getId()
        ]);

        if (!$bpt) {
            throw new MissedOrUnknown('transition');
        }

        return $bpt;
    }

    /**
     * @param IBPTransition|IExtensionBPTransition $bpt
     */
    protected function getTargetBody(IBPTransition $bpt): array
    {
        $targetBody = $bpt->getTargetFields();

        return $targetBody;
    }

    protected function getTargetAppDispatcher(IBPTransition $bpt): IAppDispatcher
    {
        /**
         * @var IExtensionAppSettings $app
         */
        $app = $bpt->getTarget()->getApplication();
        return $app->getDispatcher();
    }
}
