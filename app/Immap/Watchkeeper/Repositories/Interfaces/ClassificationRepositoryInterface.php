<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface ClassificationRepositoryInterface extends GenericRepositoryInterface {
    public function getClassificationTypeById($id);
    public function getAllClassificationType();
    public function getAllIncidentType();
    public function getAllIncidentCategory();
    public function getAllSourceGrade();
    public function getAllPoiType();
    public function getAllThreatCategory();
    public function getAllThreatType();
    public function getAllRiskClassification();
    public function getAllMovementState();
}
