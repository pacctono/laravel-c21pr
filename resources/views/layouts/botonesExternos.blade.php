      <div class="position-fixed rounded d-sm-none d-md-block"
              style="top:25%;min-height:200px;width:60px;right:10px;z-index:100;background-color:#cccccc;">
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://www.instagram.com/c21puentereal/?hl=es-la" target="_blank">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/instagram.png') }}"
                  alt="Instagram" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://twitter.com/c21puentereal" target="_blank">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/twitter.png') }}"
                  alt="Twiter" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://es-la.facebook.com/c21puentereal/" target="_blank">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/facebook.png') }}"
                  alt="Facebook" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
        @auth
          <a class="btn btn-link m-0 p-0" href="https://web.whatsapp.com/" target="_blank">
        @else
          <a class="btn btn-link m-0 p-0" href="https://api.whatsapp.com/send?phone=+582814180885" target="_blank">
        @endauth
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/whatsapp.png') }}"
                  alt="Whatsapp" data-toggle="tooltip" title="Web de Whatsapp" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://web.telegram.org/#/login" target="_blank">
            <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/telegram.png') }}"
                alt="Telegram" data-toggle="tooltip" title="Web de Telegram" style="width:50px;height:50px;">
          </a>
        </div>
        <!--div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="" target="_blank">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Chat.png') }}"
                alt="Chat" data-toggle="tooltip" title="Chat (no aplicado aun)" style="width:50px;height:50px;">
          </a>
        </div-->
      </div>