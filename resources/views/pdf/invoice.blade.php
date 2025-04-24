<div style="width: 80%; margin: 0 auto; font-family: serif; font-size: 14px; line-height: 1.5;">
    <<div style="text-align: center; margin-bottom:0px; border:4px solid black">
        <img src='images/logo.png' style="width: 200px; height:40px; margin-top:25px; margin-bottom:25px">
    </div>
  <!-- Título -->
  <h2 style="text-align: center; margin-bottom: 30px;">Invoice</h2>

  <!-- Información del cliente -->
  <p><strong>Client:</strong> {{ $reservation->user->name }} {{ $reservation->user->surname }}</p>
  <p><strong>Hotel:</strong> {{ $reservation->hotel->name }}</p>
  <p><strong>Room:</strong> {{ $reservation->room->type }}</p>
  <p><strong>Dates:</strong> {{ $reservation->check_in }} to {{ $reservation->check_out }}</p>
  <p><strong>Price per night:</strong> {{ $reservation->room->price }} €</p>
  <p><strong>Nights:</strong> {{ $days }}</p>

  <hr style="margin: 30px 0;">

  <!-- Total -->
  <div style="text-align: right;">
      <p style="font-size: 16px;"><strong>Total Price:</strong> {{ $reservation->price }} €</p>
  </div>
  <div style="height: 350px;"></div>
  <hr style="margin: 20px 0;">

    <div style="font-size: 11px; color: #555; text-align: center;">
    <p><strong>Privacy & Legal Notice</strong></p>
    <p>This invoice is issued exclusively for the use of the client listed above. Personal data is handled in accordance with our privacy policy and applicable regulations. </p>
    <p>If you have any questions about your stay or this invoice, please contact us:</p>
    <p> Phone: {{$reservation->hotel->telephone_number}}</p>
</div>

</div>
