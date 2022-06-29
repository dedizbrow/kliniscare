$(document)
.on("click",".btn-print",function(){
	var area=$(this).data('printarea');
	$("."+area).print({
	    addGlobalStyles : false,
	    stylesheet : null,
	    rejectWindow : true,
	    noPrintSelector : ".no-print",
	    iframe : true,
	    append : null,
	    prepend : null
	});
})
.on("change","#select_month,#select_year",function(){
	var year=$("#select_year").val();
	var month=$("#select_month").val();
	location.href='?ym='+year+'-'+month;
})