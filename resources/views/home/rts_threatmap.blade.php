<div class="rts-about-us-area-8 rts-section-gapTop pb--100"
    style="background:#000;">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- TEXT -->
            <div class="col-lg-6">
                <div class="title-style-one left mb--30">
                    <span class="pre" style="color:#ef4444;">
                        {{ $threatmap ? $threatmap->pre_title : 'Cyber Threat Map' }}
                    </span>

                    <h2 class="title uppercase"
                        style="color:#ffffff; line-height:1.3;">
                        {{ $threatmap ? $threatmap->title : 'Tetap Update
                        dengan Serangan Siber' }}
                    </h2>

                    <p style="color:#9ca3af; margin-top:16px; max-width:420px;">
                        {{ $threatmap ? $threatmap->subtitle : 'Informasi serangan real-time seluruh dunia
                        dapat dipantau langsung melalui peta siber ini.'}}
                    </p>
                     <div class="button-wrapper">
                        <a href="{{ $threatmap->cta_url ?? '#' }}" class="rts-btn btn-primary btn-white">{{ $threatmap->cta_tex ?? 'View Live Threat Map' }}</a>
                    </div>
                </div>
            </div>

            <!-- IMAGE / ANIMATION -->
            <div class="col-lg-6 text-center">
                <img
                    src="{{ asset('cyber_gif.webp') }}"
                    alt="Cyber Threat Map"
                    style="
                        width:100%;
                        max-width:720px;
                    ">
            </div>

        </div>
    </div>
</div>
