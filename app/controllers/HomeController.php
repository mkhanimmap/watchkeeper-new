<?php

class HomeController extends BaseController {

    public function showWelcome()
    {
        return View::make('hello');
    }


    public function showHome()
    {
        $securityAdvisoryRepo = App::make('Immap\Watchkeeper\Repositories\Interfaces\SecurityAdvisoryRepositoryInterface');
        $threatRepo = App::make('Immap\Watchkeeper\Repositories\Interfaces\ThreatRepositoryInterface');


        $threats = Immap\Watchkeeper\Threat::
            join('countries',function($join){
                $join->on('threats.country_id', '=', 'countries.id')
                     ->where('countries.active', '=', true);
            })
            ->join('users', 'threats.user_id','=','users.id')
            ->join('classifications as source_grades', 'threats.source_grade_id','=', DB::raw('source_grades.id'))
            ->join('classifications as threat_types', 'threats.threat_type_id','=',DB::raw('threat_types.id'))
            ->join('classifications as threat_categories', 'threats.threat_category_id','=',DB::raw('threat_categories.id'))
            ->select('threats.id',
            'threats.threat_datetime',
            'threats.description',
            'threats.title',
            'threats.advice',
            'threats.source',
            'threats.location',
            'threats.geojson',
            'threats.user_id',
            'users.firstname',
            'users.middlename',
            'users.lastname',
            'threats.country_id',
            'countries.name as country_name',
            'countries.code_a3 as country_code_a3',
            'countries.code_a2 as country_code_a2',
            'threats.source_grade_id',
            DB::raw('source_grades.code as source_grade_code'),
            DB::raw('source_grades.name as source_grade_name'),
            'threats.threat_type_id',
            DB::raw('threat_types.code as threat_type_code'),
            DB::raw('threat_types.name as threat_type_name'),
            'threats.threat_category_id',
            DB::raw('threat_categories.code as threat_category_name'),
            DB::raw('threat_categories.name as threat_category_name'))
            ->get();
        $callback = function($query) {
            $query->with('country','pointArea','incidentType');
        };
        $securityAdvisories = $securityAdvisoryRepo->orderBy(array('callback' => $callback));
        return View::make('home.index',compact('securityAdvisories','threats'));
    }
}
