<?php
$ID = generateRandomString(2);
$IRS =  "IRS".$ID;
$RAN = $IRS.rand(10,100);
?>
           <section class="wrapper">
                <h3><i class="fa fa-angle-right"></i> ADD USERS</h3>
                <!-- BASIC FORM ELELEMNTS -->
                <!-- /row -->
                <!-- INLINE FORM ELELEMNTS -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-panel">
                            <h4 class="mb"><i class="fa fa-angle-right"></i>AD USERS</h4>
                            <form class="" role="form" method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fullname">FULLNAME</label>
                                            <input type="text" name="staffid" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter FullName">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fullname">EMAIL</label>
                                            <input type="text" name="email" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fullname">STAFF ID</label>
                                            <input type="text" name="staffID" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter StaffID" value="<?php echo $RAN;?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label for="fullname">PHONE NUMBER</label>
                                            <input type="text" name="phone_number" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter Phpne Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label for="fullname">Date of Birth</label>
                                            <input type="date" name="staffid" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter Admin ID">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                            <label for="fullname">Date of Birth</label>
                                            <input type="date" name="staffid" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter Admin ID">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Address</label>
                                        <div class="form-group">
                                            <textarea style="resize: none;" class="form-control" name="" id="" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Image Upload</label>
                                                    <div>
                                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" alt="" />
                                                            </div>
                                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                            <div>
                                                                <span class="btn btn-theme02 btn-file">
                                                                <span class="fileupload-new"><i class="fa fa-paperclip"></i> Select image</span>
                                                                <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                                                <input type="file" class="default" />
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <span class="label label-info">NOTE!</span>
                                                        <span>
                                                            Attached image thumbnail is
                                                            supported in Latest Firefox, Chrome, Opera,
                                                            Safari and Internet Explorer 10 only
                                                        </span>
                                                    </div>
                                                </div>
                                                 <!-- /form-panel -->
                                    </div>
                                </div>
                                <input name="add_admin" type="submit" class="btn btn-theme form-control" value="Add">
                            </form>
                        </div>
                        <!-- /form-panel -->
                    </div>
                    <!-- /col-lg-12 -->
                </div>
                <!-- /row -->
                <!-- INPUT MESSAGES -->
                <!-- /row -->
            </section>
    <!-- js placed at the end of the document so the pages load faster -->