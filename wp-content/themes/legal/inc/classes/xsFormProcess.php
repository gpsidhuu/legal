<?php

class xsFormProcess
{
    private $error = [];
    private $okMsg;
    private $data;
    private $actions = array(
        'add_test',
        'register',
        'purchase_credit',
        'update_bus_profile',
        'add_coupon',
        'validate_coupon',
        'get_code',
        'add_branch',
        'code_shared',
        'send_love',
        'mark_used'
    );
    private $current_action;
    private $redirect;

    function add_test()
    {
        //
        $this->okMsg = "Addeed SUcces";
    }

    function __construct()
    {
        $this->current_action = trim($_POST['action']);
        //
        //if ($_POST)
        if (!in_array($this->current_action, $this->actions)) {
            return;
        }
        $varFunction = $this->current_action;
        $this->$varFunction();
    }

    function mark_used()
    {
        $c = new xsCoupon();
        $c->get_by_code($_POST['code']);
        if ($c->is_used()) {
            $this->error[] = "Coupon is already used";
        } else {
            $c->mark_used($_POST['bid']);
            ///
            $used = get_post_meta($_POST['bid'], 'xs_coupons_used', TRUE);
            $used++;
            update_post_meta($_POST['bid'], 'xs_coupons_used', $used);
            $this->okMsg = "Marked Used";
        }
    }

    function code_shared()
    {
        $shared = get_user_meta(get_current_user_id(), 'xs_shared_count', TRUE);
        $shared++;
        $shared = update_user_meta(get_current_user_id(), 'xs_shared_count', $shared);
    }

    function add_branch()
    {
        global $f;
        $f = new xsValidate();
        $f->setRule('branch_title', 'Title', 'trim|required');
        $f->setRule('xs_phone', 'Phone', 'trim|required');
        $f->setRule('xLoc', 'Location', 'trim|required');
        $f->setRule('xLat', 'Latitude', 'trim|required');
        $f->setRule('xLngt', 'Longitude', 'trim|required');
        if ($f->validate()) {
            // create a branch
            if ($_POST['ID'] > 0) {
                $branch_id = $_POST['ID'];
                if (get_post_meta($branch_id, 'xs_owner', TRUE) != get_current_user_id()) {
                    $this->error = 'You don\'t have permissions to edit this local';
                } else {
                    $branch_id = wp_update_post(array(
                        'ID' => $branch_id,
                        'post_title' => $_POST['branch_title'],
                        'post_status' => 'publish',
                    )); // Update the post
                }
            }
            if ($branch_id > 0) {
            } else {
                $branch_id = wp_insert_post(array(
                    'post_title' => $_POST['branch_title'],
                    'post_status' => 'publish',
                    'post_type' => 'local',
                    'author' => get_current_user_id()
                ));
            }
            if ($branch_id > 0) {
                ///
                $data = array(
                    'lat' => $_POST['xLat'],
                    'lng' => $_POST['xLng']
                );
                global $wpdb;
                $wpdb->update($wpdb->prefix . 'coupons_lat_lng', $data, array('branch_id' => $branch_id));
                ///
                update_post_meta($branch_id, 'xs_phone', $_POST['xs_phone']);
                update_post_meta($branch_id, 'xs_location', $_POST['xLoc']);
                update_post_meta($branch_id, 'xs_lng', $_POST['xLng']);
                update_post_meta($branch_id, 'xs_lat', $_POST['xLat']);
                update_post_meta($branch_id, 'xs_owner', get_current_user_id());
                for ($i = 0; $i < 7; $i++) {
                    if ($_POST['wd_' . $i] == 1) {
                        update_post_meta($branch_id, 'xs_wd_' . $i, $_POST['wd_' . $i]);
                        update_post_meta($branch_id, 'xs_timing_from_' . $i, $_POST['timing_from_' . $i]);
                        update_post_meta($branch_id, 'xs_timing_to_' . $i, $_POST['timing_to_' . $i]);
                    } else {
                        delete_post_meta($branch_id, 'xs_wd_' . $i);
                        delete_post_meta($branch_id, 'xs_timing_from_' . $i);
                        delete_post_meta($branch_id, 'xs_timing_to_' . $i);
                    }
                }
                if ($_POST['ID'] > 0) {
                    $this->okMsg = 'Branch Updated';
                } else {
                    $this->okMsg = 'New Branch added';
                    $this->redirect = SURL . '/add-local/?_added=1';
                }
            } else {
                $this->error = 'An error occurred.Please try Again';
            }
        } else {
            $this->error = $f->returnErrors();
        }
    }

    function  get_code()
    {
        global $wpdb;
        if ($_POST['_code'] == ''):
            $this->error[] = 'Invalid Coupon Referral Link';
        else:
            $code = $_POST['_code'];
            $sql = "SELECT C.coupon_code from {$wpdb->prefix}xs_quiz_log L  INNER  JOIN {$wpdb->prefix}coupons C on (L.coupon_code_id=C.id) where share_code='$code'";
            $row = $wpdb->get_row($sql);
            $this->okMsg = '<h1>Coupon Code: ' . $row->coupon_code . '</h1>';
        endif;
    }

    function update_bus_profile()
    {
        global $f;
        $f = new xsValidate();
        $user = new xsUser();
        if ($user->is_business()) {
            $f->setRule('company_name', 'Company Name', 'trim|required');
        }
        $f->setRule('company_email', 'Email Id', 'trim|required|is_email|callback[xs_validate_update_email]');
        if ($_POST['pwd'] != '') {
            $f->setRule('xs_pwd', 'Password', 'required|callback[xs_pwd_match]');
        }
        //$f->setRule( 'company_desc', 'Company Description', 'trim|required' );
        //$f->setRule( 'location', 'Location', 'trim|required' );
        if ($f->validate()) {
            $user_id = get_current_user_id();
            if ($_POST['xs_pwd'] != '') {
                wp_update_user(array(
                    'ID' => $user_id,
                    'user_pass' => $_POST['xs_pwd']
                ));
            }
            update_user_meta($user_id, 'xs_company_name', $_POST['company_name']);
            update_user_meta($user_id, 'xs_location', $_POST['location']);
            update_user_meta($user_id, 'xs_lng', $_POST['lng']);
            update_user_meta($user_id, 'xs_lat', $_POST['lat']);
            update_user_meta($user_id, 'xs_tel', $_POST['tel']);
            update_user_meta($user_id, 'xs_company_desc', $_POST['company_desc']);
            for ($i = 0; $i < 7; $i++) {
                if ($_POST['wd_' . $i] == 1) {
                    update_user_meta($user_id, 'xs_wd_' . $i, $_POST['wd_' . $i]);
                    update_user_meta($user_id, 'xs_timing_from_' . $i, $_POST['timing_from_' . $i]);
                    update_user_meta($user_id, 'xs_timing_to_' . $i, $_POST['timing_to_' . $i]);
                } else {
                    delete_user_meta($user_id, 'xs_wd_' . $i);
                    delete_user_meta($user_id, 'xs_timing_from_' . $i);
                    delete_user_meta($user_id, 'xs_timing_to_' . $i);
                }
            }
            $this->okMsg = 'Profile Updated';
        } else {
            $this->error = $f->returnErrors();
        }
    }

    /**
     * Register Form
     */
    function register()
    {
        $_username = $_POST['xs_username'];
        $_email = $_POST['xs_email'];
        $_pwd = $_POST['xs_pwd'];
        //
        $sid = $_SESSION['id'];
        if ($sid > 0) {
            $fname = $_SESSION['fname'];
            $lname = $_SESSION['lname'];
            $src = $_SESSION['src'];
        }
        /////////
        global $f;
        $f = new xsValidate();
        $f->setRule('xs_username', 'Username', 'trim|required|callback[xs_validate_un]');
        $f->setRule('xs_email', 'Email ID', 'trim|required|is_email|callback[xs_validate_email]');
        $f->setRule('xs_pwd', 'Password', 'required|callback[xs_pwd_match]');
        //
        if (!in_array($_POST['xs_user_type'], array(
            'business',
            'client'
        ))
        ) {
            $f->setCustomError('terms', 'Invalid User type. Please try again');
        }
        //
        if ($_POST['terms'] != '1') {
            $f->setCustomError('terms', 'You need to accept terms and conditions.');
        }
        if ($f->validate()) {
            $userdata = [
                'user_login' => $_username,
                'user_pass' => $_pwd,
                'user_email' => $_email,
                // When creating an user, `user_pass` is expected.
            ];
            //set the  user role
            if ($_POST['xs_user_type'] == 'business') {
                $userdata['role'] = 'author';
            } else {
                $userdata['role'] = 'client';
            }
            $id = wp_insert_user($userdata); /**/
            if (!is_wp_error($id)) {
                update_user_meta($id, 'xs_' . $_SESSION['src'], $sid);
                if ($sid != '' && xsUTL::log_user($id)) {
                    $this->redirect = bp_loggedin_user_domain();
                } else {
                    $this->redirect = SURL . '/login/?_reg=1';
                }
                $this->okMsg = 'Registration Successful.Please wait..';
            } else {
                $this->error[] = 'User Cant be created at this time. Please try again';
            }
        } else {
            $this->error = $f->returnErrors();
        }
    }

    function purchase_credit()
    {
        // add items to cart
        $f = new xsValidate();
        $f->setRule('credit_number', 'Credit Count', 'trim|required|is_number');
        if ($f->validate()) {
            $replace_order = new WC_Cart();
            $replace_order->empty_cart(TRUE);
            $added = $replace_order->add_to_cart("662", $_POST['credit_number']);
            if ($added) {
                $this->okMsg = 'Redirecting to checkout page..';
                $this->redirect = SURL . '/checkout/';
            }
        } else {
            // print_r($f->returnErrors());
            $this->error = $f->returnErrors();
        }
        // return the redirect url
    }

    function add_coupon()
    {
        $function = 'wp_insert_post';
        if ($_POST['id']) {
            $user = new xsUser();
            $pass = substr($user->user_pass, 0, 16);
            if (current_user_can('edit_posts', $_POST['id'])) {
                $process_form = 1;
                $function = 'wp_update_post';
            } else {
                $this->error[] = 'Your are not authorized to perform this action';
                die;
            };
        } else {
            $process_form = 1;
        }
        if ($process_form == 1) {
            global $f;
            $f = new xsValidate();
            $raw = $_POST;
            $_branches = $_POST['branch'];
            if (count(array_filter($_POST['imgs'])) == 0) {
                $f->setCustomError('title', 'At least 1 image is required .');
            }
            if (count(array_filter($_POST['branch'])) == 0) {
                $f->setCustomError('title', 'At least Local is required.');
            }
            if (isset($_POST['discount'])) {
                $_percent_value = $_POST['discount'];
                if ($_POST['coupon_type'] == 'cool') {
                    if ($_percent_value > 100 || $_percent_value < 50) {
                        $f->setCustomError('title', 'Cool coupons can have discount in range of 50-100%');
                    }
                } else {
                    if ($_percent_value > 49 || $_percent_value < 1) {
                        $f->setCustomError('title', 'Normal & Direct coupons can have discount in range of 1-49%');
                    }
                }
            }
            $f->setRule('title', 'Coupon Title', 'trim|required|callback[check_images]');
            $f->setRule('category', 'Category', 'trim|required|is_numeric');
            $f->setRule('desc', 'Description', 'trim|required');
            $f->setRule('no_of_coupons', 'Number of Coupons', 'trim|required|is_numeric');
            $f->setRule('discount', 'Discount', 'trim|required|is_numeric');
            if ($f->validate()) {
                //check if user have valid credits
                $credit_per_coupon = 0;
                $cat_id = 0;
                switch ($_POST['coupon_type']) {
                    case 'normal':
                        $credit_per_coupon = 1;
                        $cat_id = 38;
                        break;
                    case 'cool':
                        $credit_per_coupon = 2;
                        $cat_id = 36;
                        break;
                    case 'direct':
                        $credit_per_coupon = 2;
                        $cat_id = 37;
                        break;
                }
                $credits_required = $credit_per_coupon * $_POST['no_of_coupons'];
                /** @var $xs_user xsUser */
                global $xs_user;
                if ($xs_user->can_add_coupon($credits_required) || $function == 'wp_update_post') {
                    $args = array(
                        'post_type' => 'sh_coupons',
                        'post_title' => $_POST['title'],
                        'post_content' => $_POST['desc'],
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id()
                    );
                    if ($function == 'wp_update_post') {
                        $args['ID'] = trim($_POST['id']);
                    }
                    $coupon_id = $function($args); //FUNCTION CALL
                    //set featured image
                    update_post_meta($coupon_id, '_thumbnail_id', $_POST['cover_id']);
                    if ($coupon_id > 0) {
                        update_post_meta($coupon_id, 'xs-quest_type', $_POST['quest_type']);
                        update_post_meta($coupon_id, 'xs-radius', $_POST['radius']);
                        update_post_meta($coupon_id, 'xs-branches', $_branches);
                        //
                        global $wpdb;
                        if ($function == 'wp_update_post') {
                            //delete the old coupons
                            $sql = "DELETE FROM {$wpdb->prefix}coupons_lat_lng where coupon_id=$coupon_id";
                            $wpdb->query($sql);
                        } else {
                            update_post_meta(get_the_ID(), 'xs_views', 0);
                        }
                        foreach ($_branches as $b) {
                            $wpdb->insert($wpdb->prefix . 'coupons_lat_lng', array(
                                'coupon_id' => $coupon_id,
                                'lat' => trim(get_post_meta($b, 'xs_lat', TRUE)),
                                'lng' => trim(get_post_meta($b, 'xs_lng', TRUE)),
                                'branch_id' => $b
                            ));
                        }
                        //
                        $expiry_date = DateTime::createFromFormat('m/d/Y h:i a', $_POST['expiry_date']);
                        $expiry_time = $expiry_date->format('H:i');
                        $expiry_date = $expiry_date->format('Y-m-d');
                        update_post_meta($coupon_id, 'xs-expiry_date', $expiry_date);
                        update_post_meta($coupon_id, 'xs-expiry_time', $expiry_time);
                        update_post_meta($coupon_id, 'xs-images', $raw['imgs']);
                        // add category
                        wp_set_post_terms($coupon_id, array(
                            $cat_id,
                            $_POST['category']
                        ), 'coupons_category');
                        /////////////////////////////////////////////// create coupons
                        if ($function == 'wp_update_post') {
                            $this->okMsg = 'Coupon Updated Successfully';
                            $this->redirect = SURL . '/manage-coupons/?updated=1';
                        } else {
                            update_post_meta($coupon_id, 'xs-no_of_coupons', $_POST['no_of_coupons']);
                            $coupons = new xsCoupon($coupon_id);
                            $coupons->create_codes($_POST['no_of_coupons'])->save_codes(); // create codes
                            /////////////////////////////////////////////////////////////
                            //deduct credits
                            $current_credits = $xs_user->get_credits();
                            $update_credits = $current_credits - $credits_required;
                            $xs_user->set_credits($update_credits, get_current_user_id());
                            //
                            $this->okMsg = 'Coupon Added Successfully';
                            $this->redirect = SURL . '/add-coupon/?status=1';
                        }
                    } else {
                        $this->error[] = 'An error occurred. Please try again';
                    }
                } else {
                    $this->error[] = 'Insufficient Credits . <a  href="#" class="buy-now-trigger">Purchase new credits</a> ';
                }
            } else {
                // print_r($f->returnErrors());
                $this->error = $f->returnErrors();
            }
        } else {
            $this->error[] = 'Cant Process Form';
            die;
        }
    }

    function validate_coupon()
    {
        $blinks = NULL;
        if (!is_user_logged_in()) {
            $this->error[] = 'You need be logged in as member to use this feature';
            return;
        }
        $f = new  xsValidate();
        $f->setRule('coupon_code', 'Coupon Code', 'trim|required');
        if ($f->validate()) {
            // check if code is not used
            $coupon = new xsCoupon();
            $coupon->get_by_code($_POST['coupon_code']);
            global $wpdb;
            $sql = "Select branch_id from {$wpdb->prefix}coupons_lat_lng where coupon_id=$coupon->cid";
            $rows = $wpdb->get_results($sql);
            foreach ($rows as $row) {
                $blinks .= ' <a class="label label-info mark-used" data-cid="' . $coupon->coupon_id . '" data-code="' . $_POST['coupon_code'] . '" data-bid="' . $row->branch_id . '" href="#">' . get_the_title($row->branch_id) . '</a> ';
            }
            ////
            if ($coupon->coupon_id > 0) {
                if ($coupon->get_by_code($_POST['coupon_code'])->is_used()) {
                    //user
                    $this->error[] = 'Coupon is already used';
                } else {
                    // not used
                    $this->okMsg = 'Coupon is valid . Mark as used at  ' . $blinks;
                    $this->okMsg .= '';
                }
            } else {
                $this->error[] = 'Coupon is invalid';
            }
        } else {
            $this->error = $f->returnErrors();
        }
    }

    function send_love()
    {
        if (!is_user_logged_in()) {
            $this->redirect = SURL . '/login/?_login=0';
        } else {
            global $wpdb;
            $cid = get_current_user_id();
            $loved_id = $_POST['uid'];
            $sql = "Select count(*) from {$wpdb->prefix}xs_love where lover_id = {$cid} and loved_id ={$loved_id}";
            $rows = $wpdb->get_var($sql);
            if ($rows > 0 && is_numeric($rows)) {
                $this->okMsg = 'Already Loved';
            } else {
                $loves = get_user_meta($_POST['uid'], 'xs-loves', TRUE);
                $loves++;
                update_user_meta($_POST['uid'], 'xs-loves', $loves);
                $data = array(
                    'lover_id' => get_current_user_id(),
                    'loved_id' => $_POST['uid']
                );
                $wpdb->insert($wpdb->prefix . 'xs_love', $data);
                $this->data = array('loves' => $loves);
                $this->okMsg = 'Loved';
            }
            /// make db entries
        }
    }

    function __destruct()
    {
        $this->terminate();
    }

    function terminate()
    {
        if (!in_array($this->current_action, $this->actions)) {
            return;
        }
        // TODO: Implement __destruct() method.
        if (!empty($this->error)) {
            $res = array(
                'status' => FALSE,
                'errors' => implode('<br>', $this->error),
            );
            echo json_encode($res);
        } else {
            $res = array(
                'status' => TRUE,
                'errors' => $this->okMsg,
            );
            if (!empty($this->redirect)) {
                $res['redirect'] = $this->redirect;
            }
            if (empty($this->data)) {
            } else {
                $res['data'] = $this->data;
            }
            echo json_encode($res);
        }
        die;
    }
}

