const options = document.querySelectorAll('.option');

options.forEach(option => {
    option.addEventListener('mouseover', () => {
        option.style.transform = 'scale(0.9)';
        option.style.transition = 'transform 0.3s';
    });

    option.addEventListener('mouseout', () => {
        option.style.transform = 'scale(1)';
    });

    const selectButton = option.querySelector('.select-button');
    const extraButtons = option.querySelector('.extra-buttons');

    extraButtons.style.display = "none";

    selectButton.addEventListener('click', () => {
        options.forEach(opt => {
            const buttons = opt.querySelector('.extra-buttons');
            if (buttons !== extraButtons) {
                buttons.style.display = "none";
            }
        });

        if (extraButtons.style.display === "none" || extraButtons.style.display === "") {
            extraButtons.style.display = "flex";
        } else {
            extraButtons.style.display = "none";
        }
    });
});
