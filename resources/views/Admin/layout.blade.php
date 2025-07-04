<!DOCTYPE html>
<html lang="en">

<head>
    <x-admin.header :title="$js ?? null" />
</head>

<body>

    <div class="main-wrapper">

        @if(Auth::check())
        <x-admin.bodyheader />
        <x-admin.sidebar />
        <div class="page-wrapper">
            <div class="content">
                @include($page)
            </div>
        </div>
        @else
        @include($page)
        @endif
    </div>
    <x-admin.bodyfooter />

    <x-admin.footer :js="$js ?? null" />

</body>

</html>