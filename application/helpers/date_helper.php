<?php defined('BASEPATH') or exit('No direct script access allowed.');


/**
 * @param string $dateTime: date in format YYYY-mm-dd or YYYY-mm-d HH:ii:ss
 * @return string date in format d M Y
 * example: 2024-02-23 -> 23 Feb 2024
 */
function simple(string $dateTime): string
{
    return date('d M Y', strtotime($dateTime));
}

/**
 * Note: sangat disarankan untuk menyertakan:
 *  tipe data input dan return agar user dari function tidak kebingungan
 *  contoh dari function usage
 * 
 * Tapi jangan terlalu berkutat untuk mencatat dokumentasi untuk efisiensi waktu, tulis seperlunya,
 * gunakan nama function yang mudah difahami
 * 
 */
