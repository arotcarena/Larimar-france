import React, { useRef } from 'react';
import '../../../../styles/UI/SlickCarousel/slickTheme.css';
import '../../../../styles/UI/SlickCarousel/slick.css';
import '../../../../styles/Shop/ProductShow/productImgCarousel.css';
import Slider from 'react-slick';
import { RightArrowIcon } from '../../../../UI/Icon/Arrows/RightArrowIcon';
import { LeftArrowIcon } from '../../../../UI/Icon/Arrows/LeftArrowIcon';
import { useTouchingListener } from '../../../../CustomHook/listeners/useTouchingListener';

export const ProductImgCarousel = ({pictures}) => {

    const settings = {
        dots: true,
        arrows: false,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1
    };

    const navSettings = {
        swipe: false,
        arrows: false,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
    }

    const goTo = index => {
        navSliderRef.current.slickGoTo(index);
        sliderRef.current.slickGoTo(index);
    }
    const goPrev = () => {
        navSliderRef.current.slickPrev();
        sliderRef.current.slickPrev();
    }
    const goNext = () => {
        navSliderRef.current.slickNext();
        sliderRef.current.slickNext();
    }
    const sliderRef = useRef(null);
    const navSliderRef = useRef(null);

    const handleSwipe = (direction) => {
        if(direction === 'right') {
            goPrev();
        } else if(direction === 'left') {
            goNext();
        }
    }

    if(!pictures || pictures.length === 0) {
        return;
    } 

    const isTouching = useTouchingListener();

    return (
        <div className="product-img-carousel-wrapper">
            <div className="carousel-wrapper">
                {
                    (!isTouching && pictures.length > 1) && (
                        <button onClick={goPrev} className="carousel-arrow left">
                            <LeftArrowIcon />
                        </button>
                    )
                }
                <Slider ref={sliderRef} className="product-img-carousel" {...settings} onSwipe={handleSwipe}>
                    {
                        pictures.map((picture, index) => (
                            <div  key={index} className="carousel-item">
                                <div className="product-img-wrapper" data-controller="zoomimg">
                                    <img className="product-img" src={picture.src} alt={picture.alt} />
                                </div>
                            </div>
                        ))
                    }
                </Slider>
                {
                    (!isTouching && pictures.length > 1) && (
                        <button onClick={goNext} className="carousel-arrow right">
                            <RightArrowIcon />
                        </button>
                    )
                }
            </div>
            {
                pictures.length > 1 && (
                    <div className="nav-carousel-wrapper">
                        <Slider ref={navSliderRef} className="nav-product-img-carousel" {...navSettings}>
                            {
                                pictures.map((picture, index) => (
                                    <div key={index} className="nav-carousel-item" onClick={e => goTo(index)}>
                                        <img className="nav-product-img" src={picture.src} alt={picture.alt} />
                                    </div>
                                ))
                            }
                        </Slider>
                    </div>
                )
            }
        </div>
    )
}

