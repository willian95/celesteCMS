@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid">
        
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Editar Blog
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row" id="dev-products">
                    
                        <div class="loader-cover-custom" v-if="loading == true">
                            <div class="loader-custom"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Título</label>
                                <input type="text" class="form-control" v-model="title">
                                <small v-if="errors.hasOwnProperty('title')">@{{ errors['title'][0] }}</small>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Imágen</label>
                                <input type="file" class="form-control" ref="file" @change="onImageChange" accept="image/*|video/*" style="overflow: hidden;">

                                <img id="blah" :src="imagePreview" class="full-image" style="margin-top: 10px; width: 40%">
                                <div v-if="pictureStatus == 'subiendo'" class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${imageProgress}%`}">
                                    @{{ imageProgress }}%
                                </div>
                                
                                <p v-if="pictureStatus == 'subiendo' && imageProgress < 100">Subiendo</p>
                                <p v-if="pictureStatus == 'subiendo' && imageProgress == 100">Espere un momento</p>
                                <p v-if="pictureStatus == 'listo' && imageProgress == 100">Imágen lista</p>


                                <small v-if="errors.hasOwnProperty('image')">@{{ errors['image'][0] }}</small>
                            </div>
                        </div>


                        <button style="display:none" id="update-click" @click="update()"></button>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea  id="editor1" rows="3">{!! $blog->description !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p class="text-center">
                                <button class="btn btn-success" onclick="updateBlog()">Actualizar</button>
                            </p>
                        </div>
                    </div>

                </div>

                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->


    </div>

@endsection

@push("scripts")

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'editor1' );

        function updateBlog(){
            $("#update-click").click()
        }

    </script>

    <script>
        
        const app = new Vue({
            el: '#dev-products',
            data(){
                return{
                    blogId:"{{ $blog->id }}",
                    picture:"",
                    imagePreview:"{{ $blog->image }}",
                    title:"{{ $blog->title }}",
                    createdDate:"{{ $blog->created_date }}",
                    description:"",
                    errors:[],
                    loading:false,
                    pictureStatus:"",
                    imageProgress:"",
                    finalPictureName:"",
                    mainImageFileType:"{{ $blog->main_image_file_type }}"
                }
            },
            methods:{
                
                update(){

                    if(this.pictureStatus != "subiendo"){

                        this.description = CKEDITOR.instances.editor1.getData()

                        if(this.description == ""){

                            swal({
                                text: "Debes agregar una descripción",
                                icon: "error"
                            })

                        }else{

                            this.loading = true
                            axios.post("{{ url('/blogs/update') }}", {title:this.title, image: this.finalPictureName, description: this.description, id: this.blogId, mainImageFileType: this.mainImageFileType, createdDate: this.createdDate}).then(res => {
                                this.loading = false
                                if(res.data.success == true){

                                    swal({
                                        title: "Excelente!",
                                        text: "Blog actualizado!",
                                        icon: "success"
                                    }).then(function() {
                                        window.location.href = "{{ url('/blogs/list') }}";
                                    });
                                    

                                }else{
                                    
                                    alert(res.data.msg)
                                }

                            }).catch(err => {
                                this.loading = false
                                this.errors = err.response.data.errors
                            })

                        }

                    }else{
                        swal({
                            text: "Aún hay contenido cargandose",
                            icon: "warning"
                        })
                    }
                    
                },
                onImageChange(e){
                    this.picture = e.target.files[0];

                    this.imagePreview = URL.createObjectURL(this.picture);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.view_image = false
                    this.createImage(files[0]);
                },
                createImage(file) {
                    this.file = file
                    this.mainImageFileType = file['type'].split('/')[0]

                    if(this.mainImageFileType == "image"){
                        this.uploadMainImage()
                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.picture = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }else{

                        swal({
                            text:"Formato no permitido",
                            "icon": "error"
                        })

                    }
                },
                uploadMainImage(){
                    this.imageProgress = 0;
                    let formData = new FormData()
                    formData.append("file", this.file)
                    formData.append("upload_preset", this.cloudinaryPreset)

                    var _this = this
                    var fileName = this.fileName
                    this.pictureStatus = "subiendo";

                    var config = {
                        headers: { "X-Requested-With": "XMLHttpRequest" },
                        onUploadProgress: function(progressEvent) {
                            
                            var progressPercent = Math.round((progressEvent.loaded * 100.0) / progressEvent.total);
                        
                            _this.imageProgress = progressPercent
                            
                            
                        }
                    }

                    axios.post(
                        "{{ url('/upload/picture') }}",
                        formData,
                        config                        
                    ).then(res => {
                        
                        this.pictureStatus = "listo";
                        this.finalPictureName = res.data.fileRoute

                    }).catch(err => {
                        console.log(err)
                    })

                },

            }
        })
    
    </script>

@endpush