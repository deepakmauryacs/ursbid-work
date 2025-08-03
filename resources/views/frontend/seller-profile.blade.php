<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Register</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    :root {
        --yellow: #FFBD13;
        --blue: #4383FF;
        --blue-d-1: #3278FF;
        --light: #F5F5F5;
        --grey: #AAA;
        --white: #FFF;
        --shadow: 8px 8px 30px rgba(0, 0, 0, .05);
    }







    .wrapper {
        background: var(--white);

        max-width: 576px;
        width: 100%;
        border-radius: .75rem;
        box-shadow: var(--shadow);
        text-align: center;
    }

    .wrapper h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .rating {
        display: flex;
        justify-content: center;
        align-items: center;
        grid-gap: .5rem;
        font-size: 2rem;
        color: #ff0000;
        margin-bottom: 2rem;
    }

    .rating .star {
        cursor: pointer;
    }

    .rating .star.active {
        opacity: 0;
        animation: animate .5s calc(var(--i) * .1s) ease-in-out forwards;
    }

    @keyframes animate {
        0% {
            opacity: 0;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.2);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }


    .rating .star:hover {
        transform: scale(1.1);
    }

    textarea {
        width: 100%;
        height: 50px !important;
        background: var(--light);
        padding: 1rem;
        border-radius: .5rem;
        border: none;
        outline: none;
        resize: none;
        margin-bottom: .5rem;
    }

    .btn-group {
        display: flex;
        grid-gap: .5rem;
        align-items: center;
    }

    .btn-group .btn {
        padding: .75rem 1rem;
        border-radius: .5rem;
        border: none;
        outline: none;
        cursor: pointer;
        font-size: .875rem;
        font-weight: 500;
    }

    .btn-group .btn.submit {
        background: var(--blue);
        color: var(--white);
    }

    .btn-group .btn.submit:hover {
        background: var(--blue-d-1);
    }

    .btn-group .btn.cancel {
        background: var(--white);
        color: var(--blue);
    }

    .btn-group .btn.cancel:hover {
        background: var(--light);
    }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
    .star-rating {
        display: inline-block;
        font-size: 0;
        /* Remove whitespace between inline-block elements */
        position: relative;
    }

    .star-rating .star {
        font-size: 2rem;
        color: #a8a3a3ba;
    }

    .star-rating .star.filled {
        color: gold;
    }

    .star-rating .star-percentage {
        display: inline-block;
        font-size: 2rem;
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
        white-space: nowrap;
        color: #254affd6;
        z-index: 1;
    }
    </style>
    @include('frontend.inc.header-links')

</head>

<body>


    <!-- Body main wrapper start -->
    <div class="body-wrapper">

        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>

        <div class="ltn__breadcrumb-area text-left">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner">
                            <h1 class="page-title">Seller Profile</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Seller Profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ltn__login-area pb-65">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center d-flex">
        <a href="{{ $previousUrl }}" class="btn-primary btn btn-sm">Go Back</a>
                            <h1 class="section-title">Seller Profile</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mt-3">
                        <h1 class="section-title"> Details</h1>
                        <table class="table table-border">
                            <tr>
                                <th>Name</th>
                                <td>{{ $data12->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $data12->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $data12->phone }}</td>
                            </tr>
                            <tr>
                                <th>Rating</th>
                                <td>
                                    <div class="star-rating">
                                        <span class="star-percentage"
                                            style="width: {{ $ratingPercentage }}%;">★★★★★</span>
                                        <span class="star">★★★★★</span>
                                    </div>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="col-lg-6">
                        <div class="wrapper">
                            <h3>Add Review</h3>
                            <form action="{{ url('/rating_new') }}" method="POST">
                                @csrf
                                <div class="rating">

                                    <input type="number" name="star" hidden>
                                    <i class='bx bx-star star ' style="--i: 0;"></i>
                                    <i class='bx bx-star star' style="--i: 1;"></i>
                                    <i class='bx bx-star star' style="--i: 2;"></i>
                                    <i class='bx bx-star star' style="--i: 3;"></i>
                                    <i class='bx bx-star star' style="--i: 4;"></i>
                                </div>
                                <input type="email" name="email" hidden value="{{ $data12->email }}">
                                <textarea name="review" cols="" required rows="3"
                                    placeholder="Your opinion..."></textarea>
                                <div class="btn-group">
                                    <button type="submit" class="btn submit">Submit</button>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <h1 class="section-title">Reviews</h1>
                    @foreach ($reviews as $review)
                    <div class="col-lg-12 mt-3 border">
                        <b><i>{{ $review->name }}</i></b>
                        <p>
                            <i>{{ $review->review }}</i>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>















        <script>
        const allStar = document.querySelectorAll('.rating .star')
        const ratingValue = document.querySelector('.rating input')

        allStar.forEach((item, idx) => {
            item.addEventListener('click', function() {
                let click = 0
                ratingValue.value = idx + 1

                allStar.forEach(i => {
                    i.classList.replace('bxs-star', 'bx-star')
                    i.classList.remove('active')
                })
                for (let i = 0; i < allStar.length; i++) {
                    if (i <= idx) {
                        allStar[i].classList.replace('bx-star', 'bxs-star')
                        allStar[i].classList.add('active')
                    } else {
                        allStar[i].style.setProperty('--i', click)
                        click++
                    }
                }
            })
        })
        </script>


        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>