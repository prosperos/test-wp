document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.real-estate-filter-form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const district = this.querySelector('.district').value;
            const building_type = this.querySelector('.building_type').value;
            const number_of_floors = this.querySelector('.number_of_floors').value;

            const filter = new FormData(this);
            filter.append('action', 'real_estate_search');
            filter.append('nonce', realEstateAjax.nonce);
            filter.append('district', district);
            filter.append('building_type', building_type);
            filter.append('number_of_floors', number_of_floors);

            fetch(realEstateAjax.ajaxurl, {
                method: 'POST',
                body: filter,
            })
                .then(response => response.text())
                .then(data => {
                    this.closest('.entry-content').querySelector('.realEstateResults').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
