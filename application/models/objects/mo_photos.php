<?php
/**
 * User: Elio
 * Date: 23.12.12
 *
 */
class MO_Photos extends MO_Collection
{
    private $pages;

    private $fullWidth = 945;
    private $horizontalWidth = 274; // 264 + 10
    private $verticalWidth = 129; // 119 + 10

    public function renderPages()
    {
        $horizontal = $this->getNewCollectionByVal('is_vertical', array('0'));
        $vertical = $this->getNewCollectionByVal('is_vertical', array('1'));
        $pages = array();
        /*$fullLength = $horizontal->getSize() * $this->horizontalWidth + $vertical->getSize() * $this->verticalWidth;
        $halfLength = $fullLength / 2;
        $pagesCount = ceil($halfLength / $this->fullWidth);*/
        // first generate horizontal blocks
        if ($horizontal->getSize() > 0) {
            $horizontalOdd = (bool)($horizontal->getSize() % 2);
            $horizontalPackets = array_chunk($horizontal->getCollection(), 2);
            $horizontalBlocks = array();
            foreach ($horizontalPackets as $packet) {
                $horizontalBlocks[] = array('is_vertical' => 0, 'photos' => $packet);
            }
            // fucking with the first page
            $horizontalLastBlock = array_pop($horizontalBlocks);
            if ($horizontalOdd) {
                $pages[0][0]=$horizontalLastBlock;
                unset($horizontalLastBlock);
            } else {
                $pages[0][0]=array('is_vertical' => 0, 'photos' => array(array_shift($horizontalLastBlock['photos'])));
            }
            for($i=0;$i<2;$i++){
                if(count($horizontalBlocks)>0){
                    $pages[0][] = array_shift($horizontalBlocks);
                }
            }
            // end
            $pages = array_merge($pages, array_chunk($horizontalBlocks, floor($this->fullWidth / $this->horizontalWidth)));
        }

        M_Logger::echer($pages);

        if ($vertical->getSize() > 0) {
            $verticalOdd = (bool)$vertical->getSize() % 2;
            $verticalPackets = array_chunk($vertical->getCollection(), 2);
            $verticalBlocks = array();
            foreach ($verticalPackets as $packet) {
                $verticalBlocks[] = array('is_vertical' => 1, 'photos' => $packet);
            }
            if ($verticalOdd) {
                $verticalLastBlock = array_pop($verticalBlocks);
            }
            $lastPage = count($pages) - 1;
            $lastPageBlocksCount = count($pages[$lastPage]);
            if($lastPageBlocksCount<3){
                $freeBlocksForVertical = floor(
                    ($this->fullWidth - $this->horizontalWidth * $lastPageBlocksCount) / $this->verticalWidth
                );
                for($i=0; $i<$freeBlocksForVertical; $i++){
                    if(count($verticalBlocks)>0){
                        $pages[$lastPage][]=array_shift($verticalBlocks);
                    }
                }
            }
            if(count($verticalBlocks)>0){
                $pages = $pages + array_chunk($verticalBlocks, floor($this->fullWidth / $this->verticalWidth));
            }
        }
        
        if(!empty($horizontalLastBlock) || !empty($verticalLastBlock)){
            $lastPage = count($pages) - 1;
            $size = $this->fullWidth;
            foreach($pages[$lastPage] as $block){
                $size = $size - ($block['is_vertical'] ? $this->verticalWidth : $this->horizontalWidth);
            }

            if (!empty($verticalLastBlock)) {
                $block = $verticalLastBlock;
                if ($size >= $this->verticalWidth) {
                    $pages[$lastPage][] = $block;
                    $size = $size - $this->verticalWidth;
                } else {
                    $lastPage = $lastPage + 1;
                    $pages[] = array();
                    $pages[$lastPage][] = $block;
                    $size = $this->fullWidth - $this->verticalWidth;
                }
            }

            if (!empty($horizontalLastBlock)) {
                $block = $horizontalLastBlock;
                if ($size >= $this->horizontalWidth) {
                    $pages[$lastPage][] = $block;
                    $size = $size - $this->horizontalWidth;
                } else {
                    $lastPage = $lastPage + 1;
                    $pages[] = array();
                    $pages[$lastPage][] = $block;
                    $size = $this->fullWidth - $this->verticalWidth;
                }
            }
        }

        $this->pages = $pages;
    }

    public function getPage($num){
        if(!$this->pages){
            $this->renderPages();
        }
        return $this->pages[$num];
    }

    public function getPagesSize(){
        if(!$this->pages){
            $this->renderPages();
        }
        return count($this->pages);
    }
}
