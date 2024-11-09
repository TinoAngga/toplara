<form action="">
    <div id="list-groups"></div>
</form>
<script>
    $(document).ready(function () {
        listGroups();
        $("#list-groups").sortable({
            start: function(event, ui) {

            },
            change: function(event, ui) {
                // listGroups();
            },
            update: function(event, ui) {
                storeList();
            }
        });


        $('.group-item').on('change', function (e) {
            alert( "Handler for `change` called." );
        });
    });
    function listView(data)
    {
        var view = '';
        $.each(data, function (key, value) {
            view += `<span
                        class="btn btn-primary btn-outline btn-block text-start my-2 group-item"
                        data-group-id="${value.id}"
                    >
                    <div class="row">
                        <div class="col-md-12 text-center">${value.name}</div>
                        </div>
                    </div>
                    </span>`;
        });
        return view;
    }

    function storeList()
    {
        var datas = new Array();
        $('#list-groups span').each(function() {
            if ($(this).data("group-id") == undefined) return;
            datas.push($(this).data("group-id"));
        });
        $.ajax({
            type: 'POST',
            data: {
                ids: datas,
                // _token:
            },
            url: '{{ url("admin/service-sub-category/sort") }}',
            dataType: 'JSON',
            beforeSend: function() {
                $("#list-groups").html(`
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>`);
            },
            success: function (response) {
                listGroups();
            },
        });
    }
    function listGroups()
    {
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/service-sub-category") }}?__m=list',
            dataType: 'JSON',
            success: function (response) {
                console.log(response);
                $('#list-groups').html(listView(response.data));
            }
        });
    }
</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>
