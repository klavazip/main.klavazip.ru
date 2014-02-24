<?
class RemDebug
{
   private  $inc_time;
   private    $cnt_query;
   private    $query_time;
   private  $arQueryDebugSave;

   /**
    * Начинаем считать запросы
    *
    */
   public function Start()
   {
      global $DB;
      $DB->ShowSqlStat = true; 
      
      $this->inc_time = getmicrotime();
      if($DB->ShowSqlStat)
      {
         $this->cnt_query        = $DB->cntQuery;
         $this->query_time       = $DB->timeQuery;
         $this->arQueryDebugSave = $DB->arQueryDebug;
         $DB->arQueryDebug       = array();
         
      }
   }

   /**
    * Заканчиваем считать запросы и выводим результат
    *
    * @param {bool} $bTrac - выводить трасировку
    * @return {array}
    */
   public function Output($bTrac = false)
   {
      global $DB, $APPLICATION, $USER;

      if ($USER->IsAdmin())
      {
         $this->inc_time = round(getmicrotime()-$this->inc_time, 4);

         if(($DB->cntQuery - $this->cnt_query)>0)
         {
            return array(
               "PATH"        => $_SERVER["SCRIPT_NAME"],
               "QUERY_COUNT" => $DB->cntQuery - $this->cnt_query,
               "QUERY_TIME"  => round($DB->timeQuery - $this->query_time, 4),
               "ALL_TIME"    => $this->inc_time,
               "TRAC"        => ($bTrac == true) ? $DB->arQueryDebug : '',
            );
         }
      }
      
      $DB->ShowSqlStat = false; 
   }
}

//$REM_DEBUG = new RemDebug();
