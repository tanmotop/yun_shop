<div class="fe-mod fe-mod-4" ng-class="{'fe-mod-select':Item.id == focus}" ng-style="{'background-color':Item.params.bgcolor}">
    <form action="{{Item.params.searchurl}}" method="get">
        <input type="hidden" name='i' value='{{shop.uniacid}}'/>
        <input type="hidden" name='c' value='entry'/>
        <input type="hidden" name='m' value='yun_shop'/>
        <input type="hidden" name='do' value='shop'/>
        <input type="hidden" name='p' value='list'/>
        <div class="fe-mod-4-con" ng-style="{'border-color':Item.params.bordercolor}">
            <input type="submit" class="fe-mod-4-ico" value="" ng-class="{'fe-mod-4-2-ico':Item.params.style == 'style2'}"/>
            <div class="fe-mod-4-blank" ng-class="{'fe-mod-4-2-blank':Item.params.style == 'style2'}">
                <input class="fe-mod_4-input" name="keywords" value="{{Item.params.placeholder || '输入关键字在店铺内搜索'}}" ng-style="{'color':Item.params.color}" />
            </div>
        </div>
    </form>
</div>
