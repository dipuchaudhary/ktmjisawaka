 @extends('layouts.master')
 @section('content')
        <!-- Top News Start -->
 <div class="top-news">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 tn-left">
                        <div class="row tn-slider">
                            <div class="col-md-6">
                                <div class="tn-img">
                                    <img src="{{ asset('frontend/img/image1.jpg') }}" />
                                    <div class="tn-title text-center">
                                        <a href="">कानुनी सचेतना कार्यक्रम</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tn-img">
                                    <img src="{{ asset('frontend/img/image3.jpg') }}" />
                                    <div class="tn-title text-center">
                                        <a  href="">फेरी भेटौला तथा विदाई कार्यक्रम</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tn-img">
                                    <img src="{{ asset('frontend/img/image4.jpg') }}" />
                                    <div class="tn-title text-center">
                                        <a  href="">होली मिलन कार्यक्रम</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-lg-12">
                            <div class="card col-md-6">
                                <div class="card-body" style="background-color:#28406e">
                                <p class="card-title text-center text-white">परिचय</p>
                                </div>
                                <div class="card-content scroll-text">
                                    <p style="font-weight: 600">स्थापना </p>
                                    <p class="text-justify">न्याय प्रशासन ऐन, २०७३ दफा ३ मा प्रत्येक जिल्लामा एउटा जिल्ला अदालत रहने व्यवस्था छ । जिल्ला अदालतको मुकाम रहेको स्थानमा जिल्ला सरकारी वकील कार्यालय रहने व्यवस्था सरकारी वकील नियमावली, २०५५ को नियम ४ को उपनियम ४ मा गरिएको छ । सो अनुरुप जिल्ला अदालतहरू रहेका स्थानमा जिल्ला सरकारी वकील कार्यालयहरूको स्थापना भई कार्यसम्पादन हुँदै आएको छ ।  प्रदेश नं ३ अन्तर्गत काठमाडौं जिल्ला, बबरमहलमा अवस्थित यस जिल्ला सरकारी वकिल कार्यलय, काठमाडौंको स्थापना २०४९ सालमा भएको हो । </p>
                                    <p style="font-weight: 600">अधिकारको प्रयोग </p>
                                    <p class="text-justify">नेपालको संविधानको धारा १५७ मा नेपालमा एक महान्यायाधिवक्ता रहने र निजको नियुक्ति प्रधानमन्त्रीको सिफारिसमा राष्ट्रपतिबाट हुने व्यवस्था छ । संविधानमा लेखिएदेखि बाहेक कुनै अदालत वा न्यायिक अधिकारी समक्ष नेपाल सरकारको तर्फबाट मुद्दा चलाउने वा नचलाउने भन्ने कुराको अन्तिम निर्णय गर्ने र नेपाल सरकारको तर्फबाट चलाइने मुद्दाहरूको सन्दर्भमा सम्बन्धित अदालत वा न्यायिक अधिकारी समक्ष अभियोगपत्र दर्ता गर्ने, मुद्दाको बहस पैरवी प्रतिरक्षा गर्ने एवं सरकार वा सरकारले तोकेका अधिकारीलाई कानूनी राय प्रदान गर्ने लगायतका काम, कर्तव्य र अधिकार महान्यायाधिवक्तामा निहित रहेको छ । महान्यायाधिवक्तामा निहित अधिकारहरू मातहतका सरकारी वकीलहरूलाई सुम्पन सक्ने संवैधानिक व्यवस्था अनुसार महान्यायाधिवक्ताबाट प्रत्यायोजित अधिकार यस जिल्ला सरकारी वकील कार्यालयमा कार्यरत सरकारी वकीलबाट प्रयोग हुदै आएको छ । </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 tn-left">
                        <div class="sidebar">
                            <div class="sidebar-widget">
                                <div class="news-list">
                                    <div class="nl-item">
                                        <div class="card">
                                            <div class="card-body" style="background-color:#28406e">
                                            <p class="card-title text-center text-white">सहन्यायाधिवक्ता कार्यालय प्रमुख</p>
                                            </div>
                                            <img class="card-img-bottom" src="{{ asset('frontend/img/12.jpg') }}" alt="Card image" style="width:100%; height: auto;">
                                            <p class="card-text text-center p-1">श्री रामहरि शर्मा काफ्ले </p>
                                            <p class="card-text text-center p-1">सम्पर्क नं.९८५१३५४७४९</p>
                                        </div>
                                    </div>
                                    <div class="nl-item">
                                        <div class="card">
                                            <div class="card-body" style="background-color:#28406e">
                                            <p class="card-title text-center text-white">जिल्ला न्यायाधिवक्ता/प्रवक्ता (फोन न‌:-. ०१-५३२०९८०)</p>
                                            </div>
                                            <img class="card-img-bottom" src="{{ asset('frontend/img/2.png') }}" alt="Card image" style="width:100%;height: auto">
                                            <p class="card-text text-center p-1">श्री गोकुल बहादुर निरौला</p>
                                        </div>
                                    </div>
                                    <div class="nl-item">
                                        <div class="card">
                                            <div class="card-body" style="background-color:#28406e">
                                            <p class="card-title text-center text-white">सहायक जिल्ला न्यायाधिवक्ता /सुचना अधिकारी (सम्पर्क नं. ०१ ५३२०९८०)</p>
                                            </div>
                                            <img class="card-img-bottom" src="{{ asset('frontend/img/2.png') }}" alt="Card image" style="width:100%; height: auto;">
                                            <p class="card-text text-center p-1">श्री पूर्ण बहादुर के.सी.</p>
                                        </div>
                                    </div>
                                    <div class="nl-item">
                                        <div class="card">
                                            <div class="card-body" style="background-color:#28406e">
                                            <p class="card-title text-center text-white">सहायक जिल्ला न्यायाधिवक्ता/पीडित सहायता अधिकृत (फोन न‌:-. ०१-५३३१९४२)</p>
                                            </div>
                                            <img class="card-img-bottom" src="{{ asset('frontend/img/2.png') }}" alt="Card image" style="width:100%; height: auto;">
                                            <p class="card-text text-center p-1">श्री मीना थापा</p>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
