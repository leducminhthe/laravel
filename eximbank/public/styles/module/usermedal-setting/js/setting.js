


$(document).ready(function () {

	    $('.subject').on('change', function() {
        var subject_id = $(this).find(":selected").val();
		var form = $(this).data("form");
		var model = $(this).data("model");
		var child = $(this).data("child");
		
		let modal_name = '';
		
		if(form==1) modal_name = '#modal-online';
		else if(form==2) modal_name = '#modal-offline';
	
        $.ajax({
            url: url_load_course,
            type: 'post',
            data: {
                subject_id: subject_id,
				form: form,
				model: model,
				start_date: $(modal_name+' input[name="start_date"]').val(),
				start_hour: $(modal_name+' select[name="start_hour"]').val(),
				start_minute: $(modal_name+' select[name="start_minute"]').val(),
				end_date: $(modal_name+' input[name="end_date"]').val(),
				end_hour: $(modal_name+' select[name="end_hour"]').val(),
				end_minute: $(modal_name+' select[name="end_minute"]').val()
            },
        }).done(function(data) {
			
			 $('select[name="item_id"]').empty();
			 
			 $.each(data, function (index, value) {
			
				var option = new Option(value.text, value.id);
				
				if(form=="1")
				$("#sel_online").append(option);
				else $("#sel_offline").append(option);
               
			});
			
			if(form=="1")
				$("#sel_online").trigger('change');
			else $("#sel_offline").trigger('change');
			
			if(child){
				if(form=="1") $('#sel_online').val(child).trigger('change');
				else $('#sel_offline').val(child).trigger('change');
			}
				

               
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
	
	
	   $('.remove-setting').on('click', function () {
            let item = $(this);
            let id = $(this).data('id');

            if (!id) {
                return false;
            }

            if (!confirm('Bạn có chắc muốn xóa cài đặt này?')) {
                return false;
            }

            $.ajax({
                type: "POST",
                url: url_remove_item,
                dataType: 'json',
                data: {
                    'ids': [id]
                },
                success: function (result) {
                    item.closest('tr').remove();
                }
            });
        });

        $('.edit-setting').on('click', function () {
            let id = $(this).data('id');
			let type = $(this).data('type');

            if (!id) {
                return false;
            }
			
			let modal_name = '#modal-'+type;

            $.ajax({
                type: "POST",
                url: url_edit_item,
                data: {
                    'id': id
                }
            }).done(function (result) {

                $(modal_name+' input[name="start_date"]').val(result.start_date);
				$(modal_name+' select[name="start_hour"]').val(result.start_hour);
				$(modal_name+' select[name="start_minute"]').val(result.start_minute);
				$(modal_name+' input[name="end_date"]').val(result.end_date);
				$(modal_name+' select[name="end_hour"]').val(result.end_hour);
				$(modal_name+' select[name="end_minute"]').val(result.end_minute);
				$(modal_name+' input[name="settingitem_id"]').val(result.id);
	
				
				if(result.type=="2"){
					$('#subject_online').data('child',result.item);
					$('#subject_online').val(result.subject).trigger('change');
					//$('#sel_online').val(result.item).trigger('change');
				}
				else if(result.type=="3"){
					$('#subject_offline').data('child',result.item);
					$('#subject_offline').val(result.subject).trigger('change');
					//$('#sel_offline').val(result.item).trigger('change');
				}
				else if(result.type=="4"){
					load_quiz(result.item);		
				}
								
                $(modal_name).modal('show');
            });
        });

	$('.modal').on('hidden.bs.modal', function () {
		let modal_name = '#' + ($(this).attr("id") == undefined ? "NO-ID" : $(this).attr("id"));
		
		reset_modal(modal_name);
	});
	
	$('#modal-quiz').on('shown.bs.modal	', function (e) {
		let val = $('#modal-quiz input[name="settingitem_id"]').val();
		if(!val)
		load_quiz('');
	});

	
	$('.modal input[name=start_date]').on('change', function () {
		
		let modal_name = '#'+$(this).closest('.modal').attr('id');		
		
		var start_date = $(this).val();
        var temp = start_date.split('/');
        var start_hour = $(modal_name + ' select[name="start_hour"]').val();
        var start_minute = $(modal_name + ' select[name="start_minute"]').val();

        if(temp.length ==3){
            var start_date = temp[2]+'-'+temp[1]+'-'+temp[0]+' '+start_hour+':'+start_minute+':00';

            if(start_date < start_date_main || (end_date_main!='' && start_date > end_date_main)){
                $(modal_name + ' input[name=start_date]').val('');
                $(modal_name + ' .start_date_error').html('Thời gian bắt đầu phải trong khoảng Thời gian huy hiệu');
            }else{
                $(modal_name + ' .start_date_error').html('');
            }
        }
		
	});
	
	$('.modal input[name=end_date]').on('change', function () {
		
		let modal_name = '#'+$(this).closest('.modal').attr('id');		
		
		var end_date = $(this).val();
        var temp = end_date.split('/');
        var end_hour = $(modal_name + ' select[name="end_hour"]').val();
        var end_minute = $(modal_name + ' select[name="end_minute"]').val();

        if(temp.length ==3){
            var end_date = temp[2]+'-'+temp[1]+'-'+temp[0]+' '+end_hour+':'+end_minute+':00';

            if(end_date < start_date_main || (end_date_main!='' && end_date > end_date_main)){
                $(modal_name + ' input[name=end_date]').val('');
                $(modal_name + ' .end_date_error').html('Thời gian kết thúc phải trong khoảng Thời gian huy hiệu');
            }else{
                $(modal_name + ' .end_date_error').html('');
            }
        }
		
	});


});

	function load_quiz(seled){
		
		  $.ajax({
			url: base_url + '/admin-cp/usermedal-setting/load-quiz?id='+$('input[name="id"]').val(),
			type: 'get',
			
		}).done(function(data) {
			
			 $('#sel_quiz').empty();
			 var option = new Option('Chọn kỳ thi', '');
			 $("#sel_quiz").append(option);
			 $.each(data.results, function (index, value) {
				if(seled==value.id)
					var option = new Option(value.text, value.id,true,true);
				else var option = new Option(value.text, value.id);
				$("#sel_quiz").append(option);
			   
			});
			

			$("#sel_quiz").trigger('change');

			   
		}).fail(function(data) {
			show_message('Lỗi hệ thống', 'error');
			return false;
		});
	}
	
	function reset_modal(modal_name){
	
		$(modal_name+' input[name="start_date"]').val('');
		$(modal_name+' select[name="start_hour"]').val('00');
		$(modal_name+' select[name="start_minute"]').val('00');
		$(modal_name+' input[name="end_date"]').val('');
		$(modal_name+' select[name="end_hour"]').val('00');
		$(modal_name+' select[name="end_minute"]').val('00');		
		$(modal_name+' select[name="item_id"]').val('').trigger('change');
		$(modal_name+' select[name="subject"]').val('');
		$(modal_name+' select[name="subject"]').data('child','');
		
	}