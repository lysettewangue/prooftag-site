function normalize(value) {
    return value
        .toLocaleLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

export function enhanceHomeCentres(root = document) {
    const section = root.querySelector('[data-home-centres]');

    if (! section) {
        return;
    }

    const form = section.querySelector('[data-centres-search-form]');
    const input = section.querySelector('[data-centres-search-input]');
    const suggestions = section.querySelector('[data-centres-suggestions]');
    const empty = section.querySelector('[data-centres-no-results]');
    const options = [...section.querySelectorAll('[data-centre-suggestion]')];

    if (! form || ! input || ! suggestions || ! empty) {
        return;
    }

    let activeIndex = -1;

    const visibleOptions = () => options.filter((option) => ! option.hidden);

    const setActive = (index) => {
        const visible = visibleOptions();
        activeIndex = visible.length === 0 ? -1 : Math.max(0, Math.min(index, visible.length - 1));

        options.forEach((option) => option.classList.remove('is-active'));
        const active = visible[activeIndex];

        if (active) {
            active.classList.add('is-active');
            active.scrollIntoView({ block: 'nearest' });
        }
    };

    const open = () => {
        suggestions.hidden = false;
        input.setAttribute('aria-expanded', 'true');
    };

    const close = () => {
        suggestions.hidden = true;
        input.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
        options.forEach((option) => option.classList.remove('is-active'));
    };

    const update = () => {
        const query = normalize(input.value);
        let shown = 0;

        options.forEach((option) => {
            const matches = query === '' || normalize(option.dataset.searchValue || '').includes(query);
            const display = matches && shown < 6;
            option.hidden = ! display;
            if (display) shown += 1;
        });

        empty.hidden = shown !== 0;
        open();
        activeIndex = -1;
    };

    input.addEventListener('focus', update);
    input.addEventListener('input', update);
    input.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            open();
            setActive(activeIndex + 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            setActive(activeIndex <= 0 ? visibleOptions().length - 1 : activeIndex - 1);
        } else if (event.key === 'Enter' && activeIndex >= 0) {
            const link = visibleOptions()[activeIndex]?.querySelector('a');
            if (link) {
                event.preventDefault();
                link.click();
            }
        } else if (event.key === 'Escape') {
            close();
        }
    });

    document.addEventListener('pointerdown', (event) => {
        if (! form.contains(event.target)) close();
    });

    section.querySelectorAll('[data-centre-card]').forEach((card) => {
        const pin = section.querySelector(`[data-centre-pin="${card.dataset.centreCard}"]`);
        if (! pin) return;

        card.addEventListener('mouseenter', () => pin.classList.add('is-highlighted'));
        card.addEventListener('mouseleave', () => pin.classList.remove('is-highlighted'));
        card.addEventListener('focusin', () => pin.classList.add('is-highlighted'));
        card.addEventListener('focusout', () => pin.classList.remove('is-highlighted'));
    });
}
