class Pagination {
    constructor(element, onPagination) {
        this.elementId = element;
        this.paginationElement = $(element);
        this.currentPage = 0;
        this.currentRecords = 0;
        this.totalPages = 0;
        this.totalRecords = 0;
        this.totalRecordsPerPage = 100;
        this.onPagination = onPagination;
    }

    refreshPagination(currentPage, totalRecords, currentRecords, totalRecordsPerPage) {
        this.currentPage = currentPage;
        this.totalRecords = totalRecords;
        this.currentRecords = currentRecords;
        this.totalRecordsPerPage = totalRecordsPerPage;
        const totalPages = Math.ceil(this.totalRecords / this.totalRecordsPerPage);

        const options = Array.from(Array(totalPages)).map((d, i) => `<option value="${i + 1}" ${currentPage == i + 1 ? 'selected' : ''}>${i + 1}</option>`).join('\n')
        const per_page_options = [5,10,25,50,100].map((d) => `<option value="${d}" ${this.totalRecordsPerPage == d ? 'selected' : ''}>${d}</option>`).join('\n')
        const firstBtnProp = this.currentPage > 1 ? {
            class: 'btn-primary',
            disable: ''
        } : {
            class: 'btn-secondary',
            disable: 'disabled'
        }

        const lastBtnProp = this.currentPage != totalPages ? {
            class: 'btn-primary',
            disable: ''
        } : {
            class: 'btn-secondary',
            disable: 'disabled'
        }
        
        const htmlElement = `
        <div class="p1">Records <strong>${(currentPage*totalRecordsPerPage)-(totalRecordsPerPage-1)}</strong> to <strong>${((currentPage*totalRecordsPerPage)-(totalRecordsPerPage))+Number(currentRecords)} </strong>of <strong>${totalRecords}</strong></div>
        <div class="p1">
            <button type="button" title="First" class="btn btn-md ${firstBtnProp.class}" data-jumppage="1" ${firstBtnProp.disable}><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
        </div>
        <div class="p1" >
            <button type="button" title="Previous" class="btn btn-md ${firstBtnProp.class}" data-jumppage="${this.currentPage - 1}" ${firstBtnProp.disable}><i class="fa fa-angle-left" aria-hidden="true"></i></button>
        </div>
        <div class="p1">Page <strong>${this.currentPage}</strong> of <strong> ${totalPages}</strong></div>
        <div class="p1" >
            <button type="button" title="Next" class="btn btn-md ${lastBtnProp.class}" data-jumppage="${this.currentPage + 1}" ${lastBtnProp.disable}><i class="fa fa-angle-right" aria-hidden="true"></i></button>
        </div>
        <div class="p1" >
            <button type="button" title="Last" class="btn btn-md ${lastBtnProp.class}" data-jumppage="${totalPages}" ${lastBtnProp.disable}><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
        </div>
        <div class="p1">
        Go to 
            <select class="taat_pagination_select s1">
                ${options}
            </select>
        </div>
        <div class="p1">
            Records per page
            <select class="taat_pagination_per_page s1">
                ${per_page_options}
            </select>
        </div>
        `;

        this.paginationElement.html(htmlElement);

        $(`${this.elementId} [data-jumppage]`).on('click', (event) => {
            const element = $(event.target);
            if(element.prop("tagName") == "BUTTON"){
                $(`${this.elementId} select.taat_pagination_select`).val(element.data('jumppage'));
                $(`${this.elementId} select.taat_pagination_select`).trigger('change');
            }else{
                element.closest('button').trigger('click');
            }
        })
        $(`${this.elementId} select.taat_pagination_select`).on('change', (event) => {
            this.onPagination({
                currentPage: $(`${this.elementId} select.taat_pagination_select`).val(),
                recordsPerPage: this.totalRecordsPerPage
            })

        })
        $(`${this.elementId} select.taat_pagination_per_page`).on('change', (event) => {
            $(`${this.elementId} select.taat_pagination_select`).val(1);
            this.totalRecordsPerPage = $(`${this.elementId} select.taat_pagination_per_page`).val()
            $(`${this.elementId} select.taat_pagination_select`).trigger('change');
        })
        setTimeout(()=>{
            try{
                // $('select.taat_pagination_select').selectpicker('destroy');
                // $('select.taat_pagination_per_page').selectpicker('destroy');
            }catch(error){
                console.warn(error);
            }
        },500)
    }

}