<div class="block">
    <div class="block-content block-content-full">
        <p class="text-uppercase font-w600 text-center mt-2 mb-4">
            Most Viewed Apps
        </p>
        <table class="table table-striped table-hover table-borderless table-vcenter font-size-sm">
            <thead>
                <tr class="text-uppercase">
                    <th class="font-w700 text-center">Image</th>
                    <th class="font-w700">Name</th>

                </tr>
            </thead>
            <tbody>
                @if(isset($most_viewed_apps) && !$most_viewed_apps->isEmpty() )

                    @foreach ($most_viewed_apps as $app)
                        <tr>
                            <td class="text-center">
                                <img src="{{ $app->app_image_url}}" title="{{ $app->title }}" width="40px"/>
                            </td>
                            <td>
                                <div class="font-w600 font-size-base">{{ $app->title }}</div>
                                <div class="text-muted"><i class="fa fa-eye"></i> {{ $app->views_count }}</div>
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr align="center">
                        <td  colspan=2>No apps configured</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>