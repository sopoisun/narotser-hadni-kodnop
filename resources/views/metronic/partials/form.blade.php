@extends('metronic.layout')

@section('content')
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Karyawan <small>Tambah Karyawan</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="javascript:void(0)">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="{{ url('/karyawan') }}">Karyawan</a>
                <i class="icon-angle-right"></i>
            </li>
            <li><a href="javascript:void(0)">Tambah Karyawan</a></li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-8">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-reorder"></i> Form Karyawan
                </div>
                <div class="tools">
                    <a href="" class="collapse"></a>
                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                    <a href="" class="reload"></a>
                    <a href="" class="remove"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['role' => 'form']) !!}
                    <div class="form-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Text</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter text">
                            <span class="help-block">A block of help text.</span>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-envelope"></i></span>
                                <input type="text" class="form-control" placeholder="Email Address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Left Icon</label>
                            <div class="input-icon">
                                <i class="icon-bell"></i>
                                <input type="text" class="form-control" placeholder="Left icon">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Right Icon</label>
                            <div class="input-icon right">
                                <i class="icon-microphone"></i>
                                <input type="text" class="form-control" placeholder="Right icon">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Input With Spinner</label>
                            <input class="form-control spinner" type="text" placeholder="Process something" />
                        </div>
                        <div class="form-group">
                            <label>Static Control</label>
                            <p class="form-control-static">email@example.com</p>
                        </div>
                        <div class="form-group">
                            <label>Disabled</label>
                            <input type="text" class="form-control" placeholder="Disabled" disabled>
                        </div>
                        <div class="form-group">
                            <label>Readonly</label>
                            <input type="text" class="form-control" placeholder="Readonly" readonly>
                        </div>
                        <div class="form-group">
                            <label>Dropdown</label>
                            <select class="form-control">
                                <option>Option 1</option>
                                <option>Option 2</option>
                                <option>Option 3</option>
                                <option>Option 4</option>
                                <option>Option 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Multiple Select</label>
                            <select multiple class="form-control">
                                <option>Option 1</option>
                                <option>Option 2</option>
                                <option>Option 3</option>
                                <option>Option 4</option>
                                <option>Option 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Textarea</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile1">File input</label>
                            <input type="file" id="exampleInputFile1">
                            <p class="help-block">some help text here.</p>
                        </div>
                        <div class="form-group">
                            <label class="">Checkboxes</label>
                            <div class="checkbox-list">
                                <label>
                                    <input type="checkbox"> Checkbox 1
                                </label>
                                <label>
                                    <input type="checkbox"> Checkbox 2
                                </label>
                                <label>
                                    <input type="checkbox" disabled> Disabled
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">Inline Checkboxes</label>
                            <div class="checkbox-list">
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inlineCheckbox1" value="option1"> Checkbox 1
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inlineCheckbox2" value="option2"> Checkbox 2
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inlineCheckbox3" value="option3" disabled> Disabled
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">Radio</label>
                            <div class="radio-list">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked> Option 1 Option 1
                                </label>
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Option 2
                                </label>
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled> Disabled
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">Inline Radio</label>
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios4" value="option1" checked> Option 1
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios5" value="option2"> Option 2
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optionsRadios" id="optionsRadios6" value="option3" disabled> Disabled
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Submit</button>
                        <button type="button" class="btn default">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
