var FormDropzone = function () {


    return {
        //main function to initiate the module
        init: function () {  
            Dropzone.options.myDropzone = {
                acceptedFiles: 'image/*',
                init: function() {
                    thisDropzone = this;
                    $.get(base_url+'index.php/admin_kota/upload/'+$("#directory").val(), function(data){                        
                        $.each(data, function(key,value){                       
                          var mockFile = { name: value.name, size: value.size };                           
                          thisDropzone.options.addedfile.call(thisDropzone, mockFile);           
                          thisDropzone.options.thumbnail.call(thisDropzone, mockFile, 
                          base_url+"files/images/"+$("#directory").val()+"/"+value.name);
                          // ad remove button for image loaded
                          var removeButton = Dropzone.createElement("<button class='btn btn-sm btn-block'>Remove file</button>");
                          removeButton.addEventListener("click", function(e) {
                            e.preventDefault();
                            e.stopPropagation();                         
                            if(confirm('Hapus file ini ( '+mockFile.name.replace(/ /g, '-')+') ?')){
                                $.get(base_url+'index.php/admin_kota/delete_file/'+$("#directory").val()+'/'+mockFile.name.replace(/ /g, '-'));
                                thisDropzone.removeFile(mockFile);
                            }
                          });
                          //console.log(mockFile);
                          mockFile.previewElement.appendChild(removeButton);
                        });                         
                    });
                    this.on("addedfile", function(file) {
                        // Create the remove button
                        var removeButton = Dropzone.createElement("<button class='btn btn-sm btn-block'>Remove file</button>");
                        // Capture the Dropzone instance as closure.
                        var _this = this;
                        //console.log(file);
                        // Listen to the click event
                        removeButton.addEventListener("click", function(e) {
                          // Make sure the button click doesn't submit the form:
                          e.preventDefault();
                          e.stopPropagation();
                          // Remove the file preview.                          
                          if(confirm('Hapus file ini ( '+file.name.replace(/ /g, '-')+' ) ?')){
                            $.get(base_url+'index.php/admin_kota/delete_file/'+$("#directory").val()+'/'+file.name.replace(/ /g, '-'));
                            _this.removeFile(file);
                          }
                        });

                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                    });
                }            
            }
        }
    };
}();