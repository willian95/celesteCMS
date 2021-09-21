@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-products">
        <div class="loader-cover-custom" v-if="loading == true">
			<div class="loader-custom"></div>
		</div>
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Imágenes Home
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                        <h3 class="text-center">Agregar imágen <button class="btn btn-success" data-toggle="modal" data-target="#secondaryImagesModal">+</button></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <table class="table table-bordered table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Imágen</th>
                                        <th>Progreso</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(workImage, index) in workImages">
                                        <td>@{{ index + 1 }}</td>

                                        <td>
                                            <img :src="workImage.image" style="width: 40%;">
                                        </td>
                                        <td>

                                            <div v-if="workImage.status == 'subiendo'" class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${workImage.progress}%`}">
                                                @{{ workImage.progress }}%
                                            </div>

                                            <p v-if="workImage.status == 'subiendo' && workImage.progress < 100">Subiendo</p>
                                            <p v-if="workImage.status == 'subiendo' && workImage.progress == 100">Espere un momento</p>
                                            <p v-if="workImage.status == 'listo' && workImage.progress == 100">Contenido listo</p>
                                        </td>
                                        <td>
                                            <button v-if="!workImage.status" class="btn btn-danger" ><i class="far fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>


                    </div>

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->



        <!-- Modal-->
        <div class="modal fade" id="secondaryImagesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar Imágen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="type">Imágen (jpg,png | Dimensiones recomendadas: 1350x487px )</label>
                                    <input type="file" class="form-control" ref="file" @change="onSecondaryImageChange" accept="image/*" style="overflow: hidden;">
                                    <img id="blah" :src="secondaryPreviewPicture" class="full-image" style="margin-top: 10px; width: 40%">

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" @click="addSecondaryImage()">Añadir</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@push("scripts")

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>

        const app = new Vue({
            el: '#dev-products',
            data(){
                return{
                    imagesToUpload:[],
                    workImages:[],
                    secondaryPreviewPicture:"",
                    secondaryPicture:"",

                    errors:[],
                    loading:false,

                    imagePreview:"",
                    imageHoverPreview:"",
                    file:"",
                    imageProgress:0,
                    pictureStatus:"",
                    finalPictureName:"",

                    secondaryPicture:"",
                    secondaryPreviewPicture:"",
                    fileName:"",

                    links:[],
                    currentPage:"",
                    totalPages:""


                }
            },
            methods:{

                fetchImages(){
                    
                    axios.get(
                        "{{ url('/home-image/fetch') }}"
                    ).then(res => {

                        this.workImages = res.data.images

                    }).catch(err => {
                        console.log(err)
                    })

                },
                store(image){

                    this.loading = true
                    axios.post("{{ url('/home-image/store') }}", {
                        image: image,
                    }).then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Excelente!",
                                text: "Imagen agregada!",
                                icon: "success"
                            }).then(function() {
                                window.location.href = "{{ url('/home-image/index') }}";
                            });


                        }else{

                            alert(res.data.msg)
                        }

                    }).catch(err => {

                        this.loading = false
                        this.errors = err.response.data.errors

                        swal({
                            text: "Hay campos que debes verificar!",
                            icon: "warning"
                        })

                    })



                },
                onSecondaryImageChange(e){
                    this.secondaryPicture = e.target.files[0];

                    this.secondaryPreviewPicture = URL.createObjectURL(this.secondaryPicture);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createSecondaryImage(files[0]);
                },
                createSecondaryImage(file) {

                    this.file = file

                    if(file['type'].split('/')[0] == "image"){
                        this.fileName = file['name']

                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.secondaryPicture = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }else{
                        swal({
                            text:"Debes seleccionar un archivo de imagen",
                            icon:"error"
                        })
                    }

                },
                uploadSecondaryImage(){

                    let formData = new FormData()
                    formData.append("file", this.file)

                    var _this = this
                    var fileName = this.fileName

                    var config = {
                        headers: { "X-Requested-With": "XMLHttpRequest" },
                        onUploadProgress: function(progressEvent) {

                            var progressPercent = Math.round((progressEvent.loaded * 100.0) / progressEvent.total);


                            if(_this.workImages.length > 0){

                                _this.workImages.forEach((data,index) => {

                                   if(data.originalName == fileName){
                                    _this.workImages[index].progress = progressPercent
                                   }

                                })

                            }

                        }
                    }

                    axios.post(
                        "{{ url('/upload/picture') }}",
                        formData,
                        config
                    ).then(res => {
                        this.workImages.forEach((data, index) => {

                            let returnedName = res.data.originalName.toLowerCase()

                            if(data.originalName){
                                if(data.originalName.toLowerCase() == returnedName.toLowerCase()){
                                    this.workImages[index].status = "listo";
                                    this.workImages[index].finalName = res.data.fileRoute
                                    this.store(res.data.fileRoute)
                                }
                            }

                        })

                    }).catch(err => {
                        console.log(err)
                    })

                },
                addSecondaryImage(){

                    if(this.secondaryPicture != null){
                        this.uploadSecondaryImage()
                        this.workImages.push({image: this.secondaryPicture, status: "subiendo", originalName:this.fileName, finalName:"", progress:0})

                        this.secondaryPicture = ""
                        this.secondaryPreviewPicture = ""

                    }else{
                        swal({
                            title: "Oppss!",
                            text: "Debes añadir una imágen",
                            icon: "error"
                        });
                    }


                },
                deleteWorkImage(id){

                    this.loading = true

                    axios.post(
                        "{{ url('/home-image/delete') }}",
                        {"id": id}
                    ).then(res => {
                        
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Excelente!",
                                text: "Imagen eliminada!",
                                icon: "success"
                            }).then(function() {
                                window.location.href = "{{ url('/home-image/index') }}";
                            });


                        }else{

                            alert(res.data.msg)
                        }

                    }).catch(err => {
                        console.log(err)
                    })

                },


            },
            mounted(){

                this.fetchImages()

            }

        })

    </script>

@endpush
