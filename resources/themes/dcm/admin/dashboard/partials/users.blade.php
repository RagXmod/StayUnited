<div class="block">
    <div class="block-content block-content-full">
        <p class="text-uppercase font-w600 text-center mt-2 mb-4">
            Newly Registered Users
        </p>
        <table class="table table-striped table-hover table-borderless table-vcenter font-size-sm">
                <thead>
                <tr class="text-uppercase">
                    <th class="font-w700 text-center" style="width: 120px;">Avatar</th>
                    <th class="font-w700">Name</th>
                    <th class="font-w700"></th>
                </tr>
            </thead>
            <tbody>
                @if( isset($dashboard['latest_registered_users']))
                    @foreach($dashboard['latest_registered_users'] as $item)
                        <tr>
                            <td class="text-center">
                                <img class="img-avatar img-avatar-thumb"
                                width="50px"
                                src="{{ $item['avatar'] ?? '#' }}" alt="{{ $item['full_name'] }}">
                            </td>
                            <td>
                                <div class="font-w600 font-size-base">
                                    {{ $item['full_name'] }}
                                </div>
                                <div class="text-muted">{{ $item['email'] }}</div>
                            </td>
                            <td>
                                <a href="{{ $item['detail_url'] ?? '#' }}" class="btn btn-sm btn-dark">
                                    <i class="fa fa-link"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr align="center">
                        <td colspan="3">
                            No registered user this month.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>