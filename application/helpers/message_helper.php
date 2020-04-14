<?php

/**
 * message helper
 * $type = success, warning, error
 * $mode = flash, string
 */

function message($message, $type = 'success', $mode = 'flash')
{
    $result_message = '
    <div class="row" style="margin-bottom: 5px">
        <div class="col-md-12 text-center">
            <div id="message">
                ' . str_replace('<span class="text-danger">', '<p>', $message) . '
            </div>
        </div>
    </div>
    ';

    if ($mode != 'flash') {
        return $result_message;
    } else {
        $CI = &get_instance();
        $CI->session->set_flashdata('message', $result_message);
    }

}

function success($message, $mode = 'flash')
{
    $type    = 'success';
    $message = "<div class='alert alert-$type'>$message</div>";
    return message($message, $type, $mode);
}

function warning($message, $mode = 'flash')
{
    $type    = 'warning';
    $message = "<div class='alert alert-$type'>$message</div>";
    return message($message, $type, $mode);

}

function error($message, $mode = 'flash')
{
    $type    = 'danger';
    $message = "<div class='alert alert-$type'>$message</div>";
    return message($message, $type, $mode);

}
