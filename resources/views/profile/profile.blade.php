<div class="col-md-12 container">
    <div class="col-md-4">
        @php
        if(Auth::user()->profile_pic ==""):
            $userPic = "img/avatars/female.png";
        else:
            $userPic = Auth::user()->profile_pic;
        endif
        @endphp
        <img src="{{asset($userPic)}}" id="prof-pic" alt="" class="img-responsive img-thumbnail" height="200px" width="200px">
    </div>
    <div class="col-md-8">
        <br>
        <br>

        <div class="col-md-12">
            <label>Name: </label> {{$users->firstname ." ". $users->lastname}}
        </div>
        <div class="col-md-12">
            <label>Position: </label> {{$users->usertype->user_type_name}}
        </div>
        <div class="col-md-12">
            <form name="change-pic" id="change-pic" action="change-pic" method="POST"  >
                <label for="pic-upload"  class="btn btn-info">Change Profile Pic</label>
                <input type="file" name="pic" id="pic-upload" class="hidden">
            </form>
        </div>

    </div>
</div>
<div class="clearfix"></div>
<script>
   // document.addEventListener('DOMContentLoaded',function(){
   $(document).ready(function(){
       $("#pic-upload").change(function(){
           var pic = $(this).val();

           $("#change-pic").ajaxForm({
               //target:'#images_preview',
               beforeSubmit:function(e)
               {
               },
               success:function(e){
                   //img/avatars/male.png

                   $("#prof-pic").prop("src","img/avatars/"+e.replace(/\s/g,""));
                   $("#profile-pic").prop("src","img/avatars/"+e.replace(/\s/g,""));
               },
               error:function(e){
               }
           }).submit();


       })
   });

   // })
</script>