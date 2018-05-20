<?php

namespace Pre\Phpx;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

class Printer extends Standard
{
    protected function pStmt_Function(Stmt\Function_ $node)
    {
        return $this->nl . parent::pStmt_Function($node);
    }

    protected function pExpr_Array(Expr\Array_ $node)
    {
        return "[" . $this->pMaybeMultiline($node->items, true) . "]";
    }

    private function pMaybeMultiline(array $nodes)
    {
        return $this->pCommaSeparatedMultiline($nodes, true) . $this->nl;
    }
}
