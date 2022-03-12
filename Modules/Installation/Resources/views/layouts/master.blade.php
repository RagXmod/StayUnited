<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Installation</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <style>
        body {
            margin-top:40px;
        }
        .badge-success {
            padding: 3px 3px;
            background-color: #5cb85c;
        }
        .badge-danger {
            padding: 3px 5px;
            background-color: #d9534f;
        }
        .label {
            font-size: 13px;
            margin-left: 10px;
        }

        .wizard {
            margin-top: 40px;
        }

        .steps>ul {
            display: table;
            list-style: none;
            margin: 0 0 40px;
            padding: 10px 0;
            position: relative;
            width: 100%;
            background: #f7f7f8;
            border-radius: 5px;
        }
        .steps>ul li {
            display: table-cell;
            text-align: center;
            width: 1%;
        }
        .steps a {
            color: #337ab7;
        }
        .steps>ul li>a:before {
            border-top: 4px solid #c8c7cc;
            content: "";
            display: block;
            font-size: 0;
            height: 1px;
            overflow: hidden;
            position: relative;
            top: 21px;
            width: 100%;
            z-index: 1;
        }
        .steps li>a.done:before, .steps>ul li>a.selected:before {
            border-color: #1e9a73;
        }

        .steps>ul li>a.selected.error:before {
            border-color: #d43f3a;
        }

        .steps>ul li>a.selected .stepNumber {
            border-color: #1e9a73;
            background-color: #1e9a73;
            color: #fff;
        }
        .steps>ul .stepNumber {
            background-color: #fff;
            border: 5px solid #c8c7cc;
            border-radius: 100%;
            color: #546474;
            display: inline-block;
            font-size: 15px;
            height: 40px;
            line-height: 30px;
            position: relative;
            text-align: center;
            width: 40px;
            z-index: 2;
        }
        .steps li>a.done .stepDesc, .steps>ul li>a.selected .stepDesc {
            color: #2B3D53;
        }
        .steps>ul li:first-child>a:before {
            left: 50%;
            max-width: 51%;
        }
        .steps>ul li .stepDesc {
            color: #8e8e93;
            display: block;
            font-size: 14px;
            margin-top: 4px;
            max-width: 100%;
            table-layout: fixed;
            text-align: center;
            word-wrap: break-word;
            z-index: 104;
        }

        .steps ul li>a.done .stepNumber:before,
        .steps>ul li:last-child>a.selected .stepNumber:before {
            content: "\f00c";
            display: inline;
            float: right;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            height: auto;
            text-shadow: none;
            margin-right: 7px;
            text-indent: 0;
        }

        .steps ul li>a.done .stepNumber,
        .steps>ul li:last-child>a.selected .stepNumber {
            border-color: #1e9a73;
            background-color: #1e9a73;
            color: #fff;
            text-indent: -9999px;
        }

        .steps>ul li:last-child>a.selected.error .stepNumber:before {
            content: "\f00d";
            margin-right: 8px;
        }
        .steps>ul li:last-child>a.selected.error .stepNumber {
            color: #fff;
            background-color: #d43f3a;
            border-color: #d43f3a;
        }

        .steps>ul li:last-child>a:before {
            max-width: 50%;
            width: 50%;
        }

        .step-content {
            border: 1px solid #dadada;
            padding: 20px 30px;
            border-radius: 5px;
        }

        .logo-wrapper {
            text-align: center;
        }

        .btn-green {
            color: #fff;
            background-color: #1e9a73;
        }

        .btn-green:hover, .btn-green:active, .btn-green:focus {
            color: #fff;
            background-color: #1c916c;
        }

        .label-default {
            background-color: #aaa;
        }
    </style>
    @yield('styles')
</head>
<body style="background-color: #fff;">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-3 logo-wrapper">
                <h2>DCM Installation</h2>
            </div>
        </div>
        <div class="wizard col-md-6 offset-3">
            @yield('content')
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    <script>

        var as = {};

        as.toggleSidebar = function () {
            $(".sidebar").toggleClass('expanded');
        };

        as.hideNotifications = function () {
            $(".alert-notification").slideUp(600, function () {
                $(this).remove();
            })
        };

        as.init = function () {

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $("#sidebar-toggle").click(as.toggleSidebar);

            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();

            $(".alert-notification .close").click(as.hideNotifications);

            setTimeout(as.hideNotifications, 3500);

            $("a[data-toggle=loader], button[data-toggle=loader]").click(function () {
                if ($(this).parents('form').valid()) {
                    as.btn.loading($(this), $(this).data('loading-text'));
                    $(this).parents('form').submit();
                }
            });
        };
        $(document).ready(as.init);

        as.btn = {};
        as.btn.loading = function(button, text) {
            var oldText = button.text();
            var newText = typeof text == "undefined" ? '' : text;

            var html = '<i class="fa fa-spinner fa-spin"></i> ' + newText;
            button.data("old-text", oldText)
                .html(html)
                .addClass("disabled")
                .attr('disabled', "disabled");
        };

        as.btn.stopLoading = function (button) {
            var oldText = button.data('old-text');
            button.text(oldText)
                .removeClass("disabled")
                .removeAttr("disabled");
        };
        $("a[data-toggle=loader], button[data-toggle=loader]").click(function () {
            if ($(this).parents('form').valid()) {
                as.btn.loading($(this), $(this).data('loading-text'));
                $(this).parents('form').submit();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
