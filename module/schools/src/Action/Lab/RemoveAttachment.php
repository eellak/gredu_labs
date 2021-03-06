<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Lab;

use GrEduLabs\Schools\Service\LabServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class RemoveAttachment
{
    private $labService;

    public function __construct(LabServiceInterface $labService)
    {
        $this->labService = $labService;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }

        $lab_id = $req->getParam('lab_id', false);
        if (!$lab_id) {
            return $res->withStatus(404, 'No lab id');
        }

        $lab = $this->labService->getLabForSchool($school->id, $lab_id);
        try {
            $this->labService->removeLabAttachment($lab['id']);

            return $res->withStatus(204);
        } catch (Exception $ex) {
            return $res->withStatus(500, $ex->getMessage());
        }
    }
}
