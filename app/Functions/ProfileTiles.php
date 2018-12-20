<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 4/2/2018
 * Time: 9:15 AM
 */

namespace App\Functions;


class ProfileTiles
{
    public function queryTeam($url, $name, $position, $mobile, $email, $location) {

        return '
            <div class="col-lg-4 col-md-6">
                <div class="text-center card-box">
                    <div class="member-card">
                    
                        <div class="thumb-lg member-thumb m-b-10 center-page">
                            <img src="'.$url.'" class="rounded-circle img-thumbnail" alt="profile-image">
                        </div>
                        
                        <div class="">
                            <h4 class="m-b-5 mt-2">'.$name.'</h4>
                            <p class="text-muted">@'.$position.'</p>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">Follow</button>
                        <button type="button" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Message</button>
                        
                        <div class="text-left m-t-40">
                            <p class="text-muted font-13"><strong>Mobile :</strong><span class="m-l-15">'.$mobile.'</span></p>
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">'.$email.'</span></p>
                            <p class="text-muted font-13"><strong>Location :</strong> <span class="m-l-15">'.$location.'</span></p>
                        </div>
                        
                        <ul class="social-links list-inline m-t-30 mb-0">
                            <li class="list-inline-item">
                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Skype"><i class="fa fa-skype"></i></a>
                            </li>
                        </ul>
                        
                    </div>
                </div>
            </div>
        ';

    }
}