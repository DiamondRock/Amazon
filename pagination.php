<?php
function paginate($itemPerPage, $currentPage, $totalRecordsNum, $totalPagesNum, $pageURL, $pageURLParams="")
{
    $pagination = '';
    if ($totalPagesNum > 0 && $totalPagesNum != 1 && $currentPage >= 1 && $currentPage <= $totalPagesNum)
    { //verify total pages and current page number
        if(empty($pageURLParams))
        {
            $pageURLWithPage = $pageURL . "?page=";
        }
        else
        {
            $pageURLWithPage = $pageURL . "?" . $pageURLParams . "&page=";
        }
        $pagination = '<ul class="pagination">';
        $rightLinks = $currentPage + 2;
        $previous = $currentPage - 3; //previous link
        $next = $currentPage + 1; //next link
        $firstLink = true; //boolean var to decide our first link
        if ($currentPage > 1) 
        {
            $previousLink = ($previous <= 0) ? 1 : $previous;
            $pagination .= '<li class="first"><a href="' . $pageURLWithPage . '?page=1" title="First">«</a></li>'; //first link
            $pagination .= '<li><a href="' . $pageURLWithPage . $previousLink . '" title="Previous"><</a></li>'; //previous link
            for ($i = min($currentPage - 2, 0) + 1; $i < $currentPage; $i++)
            {
                $pagination .= '<li><a href="' . $pageURLWithPage . $i . '">' . $i . '</a></li>';
            }
            $firstLink = false; //set first link to false
        }
        if ($firstLink)
        { //if current active page is first link
            $pagination .= '<li class="first active">' . $currentPage . '</li>';
        }
        elseif ($currentPage == $totalPagesNum)
        { //if it's the last active link
            $pagination .= '<li class="last active">' . $currentPage . '</li>';
        }
        else
        { //regular current link
            $pagination .= '<li class="active">' . $currentPage . '</li>';
        }

        for ($i = $currentPage + 1; $i <= min($totalPagesNum , $rightLinks); $i++)
        { //create right-hand side links
                $pagination .= '<li><a href="' . $pageURLWithPage . $i . '">' . $i . '</a></li>';
        }
        if ($currentPage < $totalPagesNum)
        {
            $nextLink = ($i > $totalPagesNum) ? $totalPagesNum : $i;
            $pagination .= '<li><a href="' . $pageURLWithPage . $nextLink . '" >></a></li>'; //next link
            $pagination .= '<li class="last"><a href="' . $pageURLWithPage . $totalPagesNum . '" title="Last">»</a></li>'; //last link
        }
        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}