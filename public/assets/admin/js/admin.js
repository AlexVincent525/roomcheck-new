(function ($) {
    "use strict";

    $(document).ready(function() {
        $changelogAction.getChangelog('/data/changelogs.json')
            .done((response) => $changelogAction.loadChangelog(response, '.timeline'))
            .fail((xhr, status) => {
                $swal.errorWithStatus("获取时间线数据失败", xhr, status);
            });
    });

    let $changelogAction = {
        getChangelog: (url) => $.getJSON(url),
        loadChangelog: (response, selector) => {
            const $timeline = $(selector);
            const changelogs = response[`changelogs`];
            $.each(changelogs, (i, changelog) => {
                const dateid  = `time-label-${i}`;
                const logDate = changelog['date'];
                $timeline.append(`
                    <li class="time-label" id="${dateid}">
                        <span class="bg-aqua">${logDate}</span>
                    </li>
                `);
                $.each(changelog["rows"], (j, rows) => {
                    const rowsid = `rows-${rows['id']}`;
                    $timeline.append(`
                        <li class="${dateid} ${rowsid}">
                            <i class="fa fa-user bg-blue"></i>
                            <div class="timeline-item">
                            <span class="time">
                                <i class="fa fa-clock-o"></i>
                                ${rows["time"]}
                            </span>
                            <h3 class="timeline-header no-border">
                        </li>
                    `);
                    const $h3 = $(`.${dateid}.${rowsid}>div>h3`);
                    $h3.append(`
                        <span class="timeline-text-${rows["type"]}"></span>
                        <span>${rows["content"]}</span>
                    `);
                });
            });
        }
    };

})(jQuery);