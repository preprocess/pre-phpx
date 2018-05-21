<?php

namespace Pre\Phpx;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

class Printer extends Standard
{
    protected function pStmts(array $nodes, bool $indent = true) : string
    {
        $nl = $this->nl;
        return preg_replace("#\{\s*{$nl}\s*{$nl}#", "{{$nl}", parent::pStmts($nodes, $indent));
    }

    protected function pStmt_Function(Stmt\Function_ $node)
    {
        return $this->nl . parent::pStmt_Function($node);
    }

    protected function pExpr_Print(Expr\Print_ $node)
    {
        return $this->nl . $this->pPrefixOp(Expr\Print_::class, 'print ', $node->expr);
    }

    protected function pStmt_Namespace(Stmt\Namespace_ $node)
    {
        $nl = $this->nl;
        $name = is_null($node->name) ? " " : $this->p($node->name);

        if ($this->canUseSemicolonNamespaces) {
            return "{$nl}namespace {$name};{$nl}" . $this->pStmts($node->stmts, false);
        }

        return "{$nl}namespace {$name}{$nl}{" . $this->pStmts($node->stmts) . "{$nl}}";
    }

    protected function pExpr_Ternary(Expr\Ternary $node)
    {
        $nl = $this->nl;

        if (is_null($node->if)) {
            $results = $this->pInfixOp(
                Expr\Ternary::class,
                $node->cond,
                "?: ",
                $node->else
            );
        } else {
            $results = $this->pInfixOp(
                Expr\Ternary::class,
                $node->cond,
                " ?{$nl}" . $this->p($node->if) . " :{$nl}",
                $node->else
            );
        }

        return $this->indentMore($results);
    }

    protected function indentMore($code, $indent = "    ")
    {
        $nl = $this->nl;
        return preg_replace("#({$nl})(\s*)#", "$1{$indent}$2", $code);
    }

    protected function pExpr_Array(Expr\Array_ $node)
    {
        if (count($node->items) < 1) {
            return "[]";
        }

        return "[" . $this->pMaybeMultiline($node->items, true) . "]";
    }

    private function pMaybeMultiline(array $nodes)
    {
        return $this->pCommaSeparatedMultiline($nodes, true) . $this->nl;
    }
}
