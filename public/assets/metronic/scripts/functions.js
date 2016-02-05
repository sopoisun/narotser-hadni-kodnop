function validasi(){
	if($("#new_password").val() != $("#conf_password").val()){
		alert("Konfirmasi password tidak sama !!!");		
	}
	return false;
}

function ambil_komik(){
	var _author = $("#cmb_author").val();
	$.get(base_url+'index.php/webadmin/ambil_comic/'+_author,function(data){
		var json = $.parseJSON(data);
		var _html = "";
		$.each(json,function(i,v){
			if(i == 0){
				paging(v.kode_comic);
			}
			_html += "<option value='"+v.kode_comic+"'>"+v.judul_comic+"</option>";
		});
		$("#cmb_komik").html(_html);		
	});
}

function ambil_paging(){
	var _comic = $("#cmb_komik").val();
	paging(_comic);
}

function paging(kode){
	$.ajax({
		type: "GET",
        url: base_url+'index.php/webadmin/ambil_paging_chapter/'+kode,
		success: function(data){
			if(data > 0){
				_html_2 = "";
				i = 0;
				while(i < data){
					_html_2 += "<option>"+(i+1)+"</option>";
					i++;
				}
				$("#cmb_paging").html(_html_2);
			}else{
				$("#cmb_paging").html("<option>1</option>");
			}
		}
	});
}

function tampilkan(){
	var _author = $("#cmb_author").val();
	var _comic = $("#cmb_komik").val();
	var _page = $("#cmb_paging").val();
	//alert("Auhtor : "+_author+",Comic : "+_comic+",Paging : "+_page);
	$.get(base_url+'index.php/webadmin/ambil_chapter/'+_comic+'/'+_page,function(data){
		var json = $.parseJSON(data);
		var _html = "";
		$.each(json,function(i,v){
			_html += "<tr>";
				_html += "<td>"+v.judul_comic+"</td>";
				_html += "<td>"+v.no_chapter+"</td>";
				_html += "<td>"+v.nama_author+"</td>";
				_html += "<td>"+v.tanggal+"</td>";
				_html += "<td>";
					_html += '<a href="'+base_url+'index.php/webadmin/edit_chapter/'+v.kode_chapter+'" class="btn btn-sm yellow"><i class="icon-edit"></i> Edit</a>';				
					_html += '<a href="'+base_url+'index.php/webadmin/delete_chapter/'+v.kode_chapter+'/'+v.kode_comic+'/'+v.no_chapter+'" onclick="return confirm(\'Yakin hapus data ini ?\')" class="btn btn-sm red"><i class="icon-trash"></i> Hapus</a>';				
				_html += "</td>";
			_html += "</tr>";
		});
		$("#tabel_konten").html(_html);	
	});
}

/* member */
function ambil_paging_member(){
	var _comic = $("#cmb_komik_member").val();
	paging_member(_comic);
}

function paging_member(kode){
	$.ajax({
		type: "GET",
        url: base_url+'index.php/member/ambil_paging_chapter/'+kode,
		success: function(data){
			if(data > 0){
				_html_2 = "";
				i = 0;
				while(i < data){
					_html_2 += "<option>"+(i+1)+"</option>";
					i++;
				}
				$("#cmb_paging_member").html(_html_2);
			}else{
				$("#cmb_paging_member").html("<option>1</option>");
			}
		}
	});
}

function tampilkan_member(){
	var _author = $("#cmb_author").val();
	var _comic = $("#cmb_komik_member").val();
	var _page = $("#cmb_paging_member").val();
	//alert("Auhtor : "+_author+",Comic : "+_comic+",Paging : "+_page);
	$.get(base_url+'index.php/member/ambil_chapter/'+_comic+'/'+_page,function(data){
		var json = $.parseJSON(data);
		var _html = "";
		$.each(json,function(i,v){
			_html += "<tr>";
				_html += "<td>"+v.judul_comic+"</td>";
				_html += "<td>"+v.no_chapter+"</td>";
				_html += "<td>"+v.nama_author+"</td>";
				_html += "<td>"+v.tanggal+"</td>";
				_html += "<td>";
					_html += '<a href="'+base_url+'index.php/member/edit_chapter/'+v.kode_chapter+'" class="btn btn-sm yellow"><i class="icon-edit"></i> Edit</a>';				
					_html += '<a href="'+base_url+'index.php/member/delete_chapter/'+v.kode_chapter+'/'+v.kode_comic+'/'+v.no_chapter+'" onclick="return confirm(\'Yakin hapus data ini ?\')" class="btn btn-sm red"><i class="icon-trash"></i> Hapus</a>';				
				_html += "</td>";
			_html += "</tr>";
		});
		$("#tabel_konten").html(_html);	
	});
}

function ckeditor_load(){
	var ckeditor = CKEDITOR.replace('editor',{
		height:'400px',
		filebrowserBrowseUrl : base_url+'assets/new-admin/kcfinder/browse.php?type=files',
		filebrowserImageBrowseUrl : base_url+'assets/new-admin/kcfinder/browse.php?type=images',
		filebrowserFlashBrowseUrl : base_url+'assets/new-admin/kcfinder/browse.php?type=flash',
		filebrowserUploadUrl : base_url+'assets/new-admin/kcfinder/upload.php?type=files',
		filebrowserImageUploadUrl : base_url+'assets/new-admin/kcfinder/upload.php?type=images',
		filebrowserFlashUploadUrl : base_url+'assets/new-admin/kcfinder/upload.php?type=flash'		
	});
}