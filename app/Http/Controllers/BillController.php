<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{

    /**
     * @OA\Tag(
     *     name="Bills",
     *     description="API Endpoints for managing bills"
     * )
     */
    /**
     * @OA\Get(
     *     path="/api/bills",
     *     tags={"Bills"},
     *     summary="List bills with pagination",
     *    @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of bills"
     *     )
     * )
     */
    public function index()
    {
        return Bill::orderBy('id', 'desc')->paginate(5);
    }

    /**
     * @OA\Get(
     *     path="/api/bills/{code}",
     *     tags={"Bills"},
     *     summary="View a bill with details, subtotal, discount, tax, and grand total",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Bill Code",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bill details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="bill", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="sub_total", type="number", format="float"),
     *                 @OA\Property(property="discount", type="number", format="float"),
     *                 @OA\Property(property="tax_rate", type="number", format="float"),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="paid", type="boolean")
     *             ),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="amount", type="number", format="float")
     *                 )
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bill not found"
     *     )
     * )
     */
    public function show($code)
    {
        $bill = Bill::with('items')->where('code', $code)->first();

        // Calculate subtotal
        $subtotal = $bill->items->sum(fn($item) => $item->price * $item->quantity);

        // Calculate discount (assume discount is a fixed amount stored on bill)
        $discount = $bill->discount ?? 0;

        // Tax calculation (assume tax rate in % stored on bill, e.g. 10 for 10%)
        $taxRate = $bill->tax_rate ?? 0;
        $tax = (($subtotal - $discount) * $taxRate) / 100;

        // Grand total
        $grandTotal = $subtotal - $discount + $tax;

        return response()->json([
            'message' => 'Bill viewed successfully',
            'bill' => $bill
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/bills/{code}/pay",
     *     tags={"Bills"},
     *     summary="Pay a bill",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Bill Code to pay",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bill details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="bill", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="sub_total", type="number", format="float"),
     *                 @OA\Property(property="discount", type="number", format="float"),
     *                 @OA\Property(property="tax_rate", type="number", format="float"),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="paid", type="boolean")
     *             ),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="amount", type="number", format="float")
     *                 )
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bill already paid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bill already paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bill not found"
     *     )
     * )
     */
    public function pay($code)
    {
        $bill = Bill::with('items')->where('code', $code)->first();

        if ($bill->paid) {
            return response()->json(['message' => 'Bill already paid'], 400);
        }

        $subtotal = $bill->items->sum(fn($item) => $item->price * $item->quantity);
        $discount = $bill->discount ?? 0;
        $taxRate = $bill->tax_rate ?? 0;
        $tax = (($subtotal - $discount) * $taxRate) / 100;
        $grandTotal = $subtotal - $discount + $tax;

        // Mark as paid
        $bill->paid = true;
        $bill->paid_at = now();
        $bill->save();

        return response()->json([
            'message' => 'Bill paid successfully',
            'bill' => $bill
        ]);
    }
}
