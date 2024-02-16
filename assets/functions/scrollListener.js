export const scrollListener = (onScrollUp, onScrollDown, sensibility = 100) => {

    let scroll = 0;

    const onScroll = () => {
        if(window.scrollY < (scroll - sensibility)) {
            scroll = window.scrollY;
            onScrollUp();
        } else if(window.scrollY > (scroll + sensibility)) {
            scroll = window.scrollY;
            onScrollDown();
        }
    }
    window.addEventListener('scroll', onScroll);
}