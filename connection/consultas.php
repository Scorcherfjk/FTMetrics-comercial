<?php

function defaultQuery()
{
    $query = "SELECT 							
            TOP 100						
            cwc.sShortName
            ,wc.sPartId
            ,SUM(wc.dPartCount) AS dPartCount
            ,SUM(wc.dTotalParts) AS dTotalParts
            ,SUM(wc.dScrapParts) AS dScrapParts
        FROM FTMetrics2.dbo.OEEWorkCell as wc
            ,FTMetrics2.dbo.OEEConfigWorkCell as cwc
        WHERE wc.lOEEConfigWorkCellId = cwc.lOEEConfigWorkCellId
        GROUP BY cwc.sShortName, wc.sPartId
        ORDER BY cwc.sShortName";

    return $query;
}

function primaryQuery($opcion,$inicio,$final)
{
    $query = "SELECT wc.lOEEWorkCellId,
            cwc.lOEEConfigWorkCellId,
            cwc.sShortName,
            wc.sPartId,
            wc.tStart,
            wc.tEnd,
            wc.dPartCount,
            wc.dTotalParts,
            wc.dScrapParts
        FROM FTMetrics2.dbo.OEEWorkCell as wc
        ,FTMetrics2.dbo.OEEConfigWorkCell as cwc
        WHERE wc.lOEEConfigWorkCellId = cwc.lOEEConfigWorkCellId
            AND wc.lOEEConfigWorkCellId = $opcion
            AND (wc.tStart >= '$inicio' OR wc.tEnd <= '$final')
            AND NOT (wc.tStart < '$inicio' OR wc.tEnd > '$final')
        ORDER BY wc.tStart";

    return $query;
}

function dropDown(){
    $query ="SELECT cwc.sShortName as ssn, wc.lOEEConfigWorkCellId as icwc
            FROM FTMetrics2.dbo.OEEWorkCell as wc, 
                FTMetrics2.dbo.OEEConfigWorkCell as cwc
            WHERE wc.lOEEConfigWorkCellId = cwc.lOEEConfigWorkCellId
            GROUP BY cwc.sShortName, wc.lOEEConfigWorkCellId 
            ORDER BY cwc.sShortName";

    return $query;   
}

function updateQuery($lOEEWorkCellId, $quantity){
    
    $query = "UPDATE FTMetrics2.dbo.OEEWorkCell
            SET dPartCount = (dPartCount - '$quantity'), dScrapParts = '$quantity'
            WHERE lOEEWorkCellId = '$lOEEWorkCellId'";

    return $query;

}
    

?>