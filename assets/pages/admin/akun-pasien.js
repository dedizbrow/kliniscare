$tableUsers = $("#tableUsers").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 25,
    ajax: {
        url: base_url('admin/administrative/load-dt-akun-pasien'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
        // { targets: [0], width: '35px',className: 'text-center' },
        // { targets: [4], width: '79px',className: 'text-center' },
        // { targets: [-1], width: '50px',className: 'text-center',searchable: false,orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
        var selected="", set_status="Inactive", btnclass="btn-danger";
        if(parseInt(data[6])==1){
            selected="checked='checked'"; set_status="Active";
            btnclass="btn-success";
        }else{
            $(row).addClass("text-danger");
        }
        $("td:eq(6)",row).html($('<label class="ckbox"><input type="checkbox" class="set_status_user" data-id="'+data[0]+'" name="is_enabled" value="1" '+selected+'> <span>'+set_status+'</span></label>'));
    },
})

$(document)
.ready(function(){
    getActiveLang('admin/administrative');
    
})
