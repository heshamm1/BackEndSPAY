<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Google\Cloud\Firestore\FirestoreClient;

class Payment extends Model
{
    use HasFactory;
    
    public $firstore;
    public $collection;
    public $documents;

    public function __construct()
    {
        $this->firstore = new FirestoreClient();
        $this->collection = $this->firstore->collection('payments');
        $this->documents = $this->collection->documents()->rows();
    }

    /**
     * get all payment
     * 
     * @return array of payment`
     */
    public function getAll()
    {
        $documents =  $this->documents;
        $payment = [];
        foreach ($documents as $document) {
            $id = $document->id();
            $payment[] = [
                'id' => $id,
                'data' => $document->data()
            ];
        }
        return $payment;
    }

    /**
     * get client by id
     * 
     * @param  int $id
     * @return array of client
     */
    public function find($id){
        $document = $this->collection->document($id)->snapshot();
        if ($document->exists()) {
            $client = [
                'client_id' => $document->id(),
                'data' => $document->data()
            ];
            return $client;
        }
        return false;
    }

    /**
     * create client
     * 
     * @param  array $data
     * @return array of client
     */
    public function create(array $data){
        $document = $this->collection->add($data);
        return $document->snapshot();
    }

    /**
     * update client
     * 
     * @param  int $id
     * @param  array $data
     * @return array of client
     */
    public function edit ($id, array $data){
        $document = $this->collection->document($id);
        $document->set($data);
        return $document->snapshot();
    }

    /**
     * delete client
     * 
     * @param  int $id
     * @return array of client
     */
    public function payments($id)
    {
        $collection = $this->firstore->collection('payments');
        $documents = $collection->where('client_id', '==', $id)->documents()->rows();
        $payments = [];
        foreach ($documents as $document) {
            $payments[] = [
                'id' => $document->id(),
                'data' => $document->data()
            ];
        }
        return $payments;
    }

    /**
     * delete payment
     * 
     * @param  int $id
     * @return array of payment
     */
    public function deletePayment($id)
    {
        $document = $this->collection->document($id);
        $document->delete();
        return $document->snapshot();
    }

}

    

