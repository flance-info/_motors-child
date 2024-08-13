document.addEventListener('DOMContentLoaded', () => {
    const cleave = new Cleave('.form-control-mileage-comma', {
        numeral: true,
		numeralThousandsGroupStyle: 'thousand'
    });
});