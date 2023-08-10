<?php
$password = generateRandomString(8);
?>
            <section class="wrapper">
                <h3><i class="fa fa-angle-right"></i> ADD ADMINS</h3>
                <!-- BASIC FORM ELELEMNTS -->
                <!-- /row -->
                <!-- INLINE FORM ELELEMNTS -->
                <div class="row mt">
                    <div class="col-lg-12">
                        <div class="form-panel">
                            <h4 class="mb"><i class="fa fa-angle-right"></i> Admins Form</h4>
                            <form class="form-inline" role="form" method="post">
                                <?php validate_adding_ADMIN()?>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail2">Admin ID</label>
                                    <input type="text" name="staffid" class="form-control" id="exampleInputEmail2"
                                        placeholder="Enter Admin ID">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail2">Admin ID</label>
                                    <input type="text" class="form-control" id="exampleInputEmail2"
                                        placeholder="" value="<?php echo $password;?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputPassword2">Role</label>
                                    <select name="department" id="" class="form-control">
                                        <option value="0">Department</option>
                                        <option value="Human Resources (HR)">Human Resources (HR)</option>
                                        <option value="IT">IT</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Operation">Operation</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputPassword2">Role</label>
                                    <select name="role" id="" class="form-control">
                                        <option value="0">Select Role</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">Manager</option>
                                        <option value="2">Supervisor</option>
                                        <option value="2">Coordinator</option>
                                    </select>
                                </div>
                                <input name="add_admin" type="submit" class="btn btn-theme" value="Add">
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
