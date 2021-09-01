@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-format">
        <div class="container">

            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                
                <div class="col-12">
                    
                    <div class="card card-custom bg-gray-100 card-stretch gutter-b">
                        <!--begin::Body-->
                        <div class="card-body p-0 position-relative overflow-hidden">
                            <!--begin::Chart-->
                            <div id="kt_mixed_widget_2_chart" class="card-rounded-bottom bg-dark" style="height: 200px"></div>
                            <!--end::Chart-->
                            <!--begin::Stats-->
                            <div class="card-spacer mt-n25">
                                <!--begin::Row-->
                                <div class="row m-0">
                                    <div class="col bg-white px-6 py-8 rounded-xl mr-7">
                                        <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
                                                    <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
                                                    <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
                                                    <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <a href="#" class="text-info font-weight-bold font-size-h6">Blog: {{ App\Models\Blog::count() }} </a>
                                    </div>
                                   
                                </div>
                                <!--end::Row-->


                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Body-->
                    </div>

                </div>

                
            </div>

        </div>
    </div>
@endsection