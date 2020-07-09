<div class="fe-mod fe-mod-cube" ng-class="{'fe-mod-select':Item.id == focus}" ng-style="{'background-color':Item.params.bgcolor}">
    <div style="line-height: 170px; text-align: center; color: #999; font-size: 16px;" ng-if="!hasCube(Item)">未设置魔方</div>
    <div class="inner">
        <table>
            <tr ng-repeat="row in Item.params.layout track by $index" ng-init="rowindex = $index">
                <td ng-init="colindex = $index" ng-repeat="col in row  track by $index" ng-if="col.cols" class="{{col.classname}} rows-{{col.rows}} cols-{{col.cols}}"
                    ng-class="{'empty' : col.isempty, 'not-empty' : !col.isempty}" 
                    rowspan="{{col.rows}}"
                    colspan="{{col.cols}}">
                    <div ng-if="!col.isempty && col.imgurl"><a href="{{col.url}}"><img ng-src="{{col.imgurl}}" style="width: 100%;height: auto;" /></a></div>
                </td>
            </tr> 
        </table>
    </div>
</div>
 
