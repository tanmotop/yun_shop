@extends('layouts.base')

@section('content')

@section('title', trans('支付宝登录'))

<script type="text/javascript">
    window.optionchanged = false;
    require(['bootstrap'], function () {
        $('#myTab .tab').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });
</script>

<section class="content">
    <form id="setform" action="" method="post" class="form-horizontal form">
        @include('Yunshop\AlipayOnekeyLogin::admin.tabs')

        <div class="info">
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane  active"
                         id="tab_setinfo">@include('Yunshop\AlipayOnekeyLogin::admin.tpl.setinfo')</div>
                    {{--<div class="tab-pane" id="tab_alipay">@include('Yunshop\AlipayOnekeyLogin::admin.tpl.alipay')</div>--}}
                </div>

                <div class="form-group col-sm-12 mrleft40 border-t">
                    <input type="submit" name="submit" value="提交" class="btn btn-success"
                           onclick="return formcheck()"/>
                </div>
            </div>
        </div>

    </form>
</section><!-- /.content -->
@endsection

