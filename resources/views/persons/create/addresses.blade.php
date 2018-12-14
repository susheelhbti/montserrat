<div class="form-group form-check">
    {!! Form::checkbox("do_not_mail", 1, 0,["class" => "form-check-input", "id" => "do_not_mail"]) !!}
    {!! Form::label("do_not_mail", "Do not mail", ["class" => "form-check-label", "id" => "do_not_mail"]) !!}
</div>
<div class="form-group">
    <ul role="tablist" class="nav nav-tabs">
        <li class="nav-item" role="tab">
            <a class="nav-link active" data-toggle="tab" role="tab" href="#address_home">
                <i class="fa fa-home"></i>
                <label>Home</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#address_work">
                <i class="fa fa-archive"></i>
                <label>Work</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#address_other">
                <i class="fa fa-cog"></i>
                <label>Other</label>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="address_home" class="tab-pane fade show active" role="tabpanel">
            <h4>Home address</h4>

            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address1", "Address Line 1:") !!}
                    {!! Form::text("address_home_address1", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address2", "Address Line 2:") !!}
                    {!! Form::text("address_home_address2", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_city", "City:") !!}
                    {!! Form::text("address_home_city", null, ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_state", "State:") !!}
                    {!! Form::select("address_home_state", $states, "1042", ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_zip", "Zip:") !!}
                    {!! Form::text("address_home_zip", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_country", "Country:") !!}
                    {!! Form::select("address_home_country", $countries, "1228", ["class" => "form-control"]) !!}
                </div>
            </div>
        </div>
        <div id="address_work" class="tab-pane fade" role="tabpanel">
            <h4>Work address</h4>

            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address1", "Address Line 1:") !!}
                    {!! Form::text("address_home_address1", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address2", "Address Line 2:") !!}
                    {!! Form::text("address_home_address2", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_city", "City:") !!}
                    {!! Form::text("address_home_city", null, ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_state", "State:") !!}
                    {!! Form::select("address_home_state", $states, "1042", ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_zip", "Zip:") !!}
                    {!! Form::text("address_home_zip", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_country", "Country:") !!}
                    {!! Form::select("address_home_country", $countries, "1228", ["class" => "form-control"]) !!}
                </div>
            </div>
        </div>
        <div id="address_other" class="tab-pane fade" role="tabpanel">
            <h4>Other address</h4>

            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address1", "Address Line 1:") !!}
                    {!! Form::text("address_home_address1", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_address2", "Address Line 2:") !!}
                    {!! Form::text("address_home_address2", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_city", "City:") !!}
                    {!! Form::text("address_home_city", null, ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_state", "State:") !!}
                    {!! Form::select("address_home_state", $states, "1042", ["class" => "form-control"]) !!}
                </div>
                <div class="col-4">
                    {!! Form::label("address_home_zip", "Zip:") !!}
                    {!! Form::text("address_home_zip", null, ["class" => "form-control"]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    {!! Form::label("address_home_country", "Country:") !!}
                    {!! Form::select("address_home_country", $countries, "1228", ["class" => "form-control"]) !!}
                </div>
            </div>
        </div>
    </div>
</div>