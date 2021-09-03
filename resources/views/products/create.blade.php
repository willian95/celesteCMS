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
                        <h3 class="card-label">Crear producto
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                                
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Título</label>
                                <input type="text" class="form-control" v-model="name">
                                <small v-if="errors.hasOwnProperty('name')">@{{ errors['name'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Ubicación</label>
                                <input type="text" class="form-control" v-model="location">
                                <small v-if="errors.hasOwnProperty('location')">@{{ errors['location'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Metros cuadrados</label>
                                <input type="text" class="form-control" v-model="squareMeter" @keypress="isNumberDot($event)">
                                <small v-if="errors.hasOwnProperty('square_meter')">@{{ errors['square_meter'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">Imágen</label>
                                <input type="file" class="form-control" ref="file" @change="onMainImageChange" accept="image/*" style="overflow: hidden;">

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

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea rows="3" id="editor1"></textarea>
                                <small v-if="errors.hasOwnProperty('description')">@{{ errors['description'][0] }}</small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                        <h3 class="text-center">Imágenes secundarias <button class="btn btn-success" data-toggle="modal" data-target="#secondaryImagesModal">+</button></h3>
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
                                            <img v-if="workImage.image.indexOf('image') >= 0" :src="workImage.image" style="width: 40%;">
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
                                            <button class="btn btn-danger" @click="deleteWorkImage(index)"><i class="far fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

    
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p class="text-center">
                                <button class="btn btn-success" @click="store()">Crear</button>
                            </p>
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
                                    <label for="type">Imágen</label>
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

                    location:"",
                    squareMeter:"",
                    description:"",
                    action:"create",
    
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
                    fileName:""

                    
                }
            },
            methods:{
                
                store(){

                    var completeUploading = true

                    this.workImages.forEach((data) => {
                        if(data.status == 'subiendo'){
                            completeUploading = false
                        }
                    })

                    if(completeUploading && this.pictureStatus == "listo"){

                        this.workImages.forEach((data) => {
                            this.imagesToUpload.push({finalName:data.finalName, type: data.type})
                        })

                        this.loading = true
                        axios.post("{{ url('/products/store') }}", {
                            name:this.name, 
                            location:this.location,
                            square_meter: this.squareMeter,
                            image: this.finalPictureName, 
                            description: CKEDITOR.instances.editor1.getData(), 
                            workImages: this.imagesToUpload, 
                            mainImageFileType: this.mainImageFileType, 
                        }).then(res => {
                            this.loading = false
                            if(res.data.success == true){

                                swal({
                                    title: "Excelente!",
                                    text: "Proyecto creado!",
                                    icon: "success"
                                }).then(function() {
                                    window.location.href = "{{ url('products/list') }}";
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


                    }else{

                        swal({
                            text:"Aún hay contenido cargandose",
                            icon:"warning"
                        })

                    }
                    
                    

                },
                
                onMainImageChange(e){
                    this.getImage(e, "main")
                },
                onHoverImageChange(e){
                    this.getImage(e, "hover")
                },
                getImage(e, type){

                    let picture = e.target.files[0];

                    if(type == "main"){
                        this.imagePreview = URL.createObjectURL(picture);
                    }else{
                        this.imageHoverPreview = URL.createObjectURL(picture);
                    }
                    
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0], type);

                },
                createImage(file, type) {
                    this.file = file
                    if(file['type'].split('/')[0] == "image"){
                        
                     
                        this.uploadMainImage(type)
                    
                    }else{

                        swal({
                            text:"Debe seleccionar un archivo de imagen",
                            icon:"error"
                        })

                    }
                    
                },
                uploadMainImage(type){
                    
                    if(type == "main"){

                        this.imageProgress = 0;
                    }else{
                        this.imageHoverProgress = 0;
                    }
                    
                    let formData = new FormData()
                    formData.append("file", this.file)

                    var _this = this
                    if(type == "main"){
                        this.pictureStatus = "subiendo";
                    }else{
                       
                        this.pictureHoverStatus = "subiendo";
                    }

                    var config = {
                        headers: { "X-Requested-With": "XMLHttpRequest" },
                        onUploadProgress: function(progressEvent) {
                            
                            var progressPercent = Math.round((progressEvent.loaded * 100.0) / progressEvent.total);
                            if(type == "main"){
                                _this.imageProgress = progressPercent
                            }else{
                                _this.imageHoverProgress = progressPercent
                            }
                            
                            
                            
                        }
                    }

                    axios.post(
                        "{{ url('/upload/picture') }}",
                        formData,
                        config                        
                    ).then(res => {
                        
                        if(type == "main"){
                            this.pictureStatus = "listo";
                            this.finalPictureName = res.data.fileRoute
                        }else{
                            this.pictureHoverStatus = "listo";
                            this.finalHoverPictureName = res.data.fileRoute
                        }
                        

                    }).catch(err => {
                        console.log(err)
                    })

                },

                onImageCategoryChange(e){
                    let newCategoryPicture = e.target.files[0];

                    this.imageCategoryPreview = URL.createObjectURL(newCategoryPicture);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createCategoryImage(files[0]);
                },
                createCategoryImage(file) {

                    this.file = file
                    if(file['type'].split('/')[0] == "image"){
                        
                     
                        this.uploadCategoryImage(type)
                    
                    }else{

                        swal({
                            text:"Debe seleccionar un archivo de imagen",
                            icon:"error"
                        })

                    }

                },
                uploadCategoryImage(type){

                    this.imageCategoryProgress = 0;
                    
                    let formData = new FormData()
                    formData.append("file", this.file)

                    var _this = this
                    this.pictureCategoryStatus = "subiendo";
     
                    var config = {
                        headers: { "X-Requested-With": "XMLHttpRequest" },
                        onUploadProgress: function(progressEvent) {
                            
                            var progressPercent = Math.round((progressEvent.loaded * 100.0) / progressEvent.total);
                          
                            _this.imageCategoryProgress = progressPercent
       
                        }
                    }

                    axios.post(
                        "{{ url('/upload/picture') }}",
                        formData,
                        config                        
                    ).then(res => {
                        
                        this.pictureCategoryStatus = "listo";
                        this.finalCategoryPictureName = res.data.fileRoute
                        
                        

                    }).catch(err => {
                        console.log(err)
                    })

                },

                storeCategory(){

                    this.loading = true
                    axios.post("{{ url('admin/category/store') }}", {name: this.newCategory, image: this.finalCategoryPictureName})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: "Haz creado una categoría!",
                                icon: "success"
                            });
                            this.newCategory = ""
                            this.imageCategoryPreview = ""
                            this.finalCategoryPictureName=""
                            this.fetchCategories()
                        }else{

                            swal({
                                title: "Lo sentimos!",
                                text: res.data.msg,
                                icon: "error"
                            });

                        }

                    })
                    .catch(err => {
                        this.loading = false
                        swal({
                            text:"Hay campos que debe corregir",
                            icon:"error"
                        })
                        this.categoryErrors = err.response.data.errors
                    })

                },
                fetchCategories(){

                    axios.get("{{ url('/category/all') }}").then(res => {
    
                        this.categories = res.data.categories

                    })

                },

                storeColor(){

                    this.loading = true
                  
                    axios.post("{{ url('admin/colors/store') }}", {name: this.newColor})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: "Haz creado un color!",
                                icon: "success"
                            });
                            this.newColor = ""
                            this.fetchColors()
                        }else{

                            swal({
                                title: "Lo sentimos!",
                                text: res.data.msg,
                                icon: "error"
                            });
                        }

                    })
                    .catch(err => {
                        this.loading = false
                        this.formatErrors = err.response.data.errors
                    })
                    

                },
                fetchColors(){
                    axios.get("{{ url('admin/colors/all') }}").then(res => {
    
                        this.colors = res.data.colors
 
                    })
                },

                addProductColor(){

                    if(this.color != null && this.color != "" && this.price != null && this.price != "" && this.stock != null && this.stock != ""){
                        this.productFormatSizes.push({color: this.color, price: this.price, stock: this.stock})

                        this.color = ""
                        this.price = ""
                        this.stock = ""
                    }else{
                        swal({
                            title: "Oppss!",
                            text: "Debes completar todos los campos",
                            icon: "error"
                        });
                    }
                    

                },
                deleteProductFormatSize(index){

                    this.productFormatSizes.splice(index, 1)

                },
                number_format(number, decimals, dec_point, thousands_point) {

                    if (number == null || !isFinite(number)) {
                        throw new TypeError("number is not valid");
                    }

                    if (!decimals) {
                        var len = number.toString().split('.').length;
                        decimals = len > 1 ? len : 0;
                    }

                    if (!dec_point) {
                        dec_point = '.';
                    }

                    if (!thousands_point) {
                        thousands_point = ',';
                    }

                    number = parseFloat(number).toFixed(decimals);

                    number = number.replace(".", dec_point);

                    var splitNum = number.split(dec_point);
                    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
                    number = splitNum.join(dec_point);

                    return number;
                },
                isNumberDot(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                },
                isNumber(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
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

                            if(data.originalName.toLowerCase() == returnedName.toLowerCase()){
                                this.workImages[index].status = "listo";
                                this.workImages[index].finalName = res.data.fileRoute
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
                deleteWorkImage(index){

                    this.workImages.splice(index, 1)

                },


            },
            mounted(){
                
                this.fetchCategories()
                this.fetchColors()
                CKEDITOR.replace( 'editor1' );

            }

        })
    
    </script>

@endpush