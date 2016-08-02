@extends('app')

@section('content')
    <div class="panel-heading">Settings</div>
    {{--<div class="panel-body">--}}
    {{--You are logged in!--}}
    {{--</div>--}}
    <div class="settings-table">
        <table class="bordered">
            <tr>
                <td>管理根目录</td>
                <form action="{{ url('/settings') }}">
                    <td><input type="text" name="root-dir"></td>
                    <td><input type="submit" value="apply"></td>
                </form>
            </tr>
        </table>
    </div>
@stop