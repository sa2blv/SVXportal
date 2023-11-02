<?php
class index  {
    private $this_module_name ="Dummy driver";
    private $station_id;
    
    public function Init($sation_id)
    {
        $this->station_id = $sation_id;
        
    }
    
    public function status_bar()
    {
        print 'Inside dummy `staus_bar()`';

    }
    public function Station_edit_adons()
    {
        print 'Inside `aMemberFunc()`';
    }
    public function Dashboard()
    {
        print 'Dummy dashboard Dashboard()' ;
        
    }
    public function Dashboard_Settings()
    {
        echo '<h3>Driver Settings for dummy</h3>';
        
    }
    
    
}