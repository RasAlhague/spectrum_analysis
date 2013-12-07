#include "mrfi.h"
#include "radios/family1/mrfi_spi.h"
void print_rssi(int8_t rssi)
{
  char output[] = {" 000 "};
  if (rssi<0) {output[0]='-';rssi=-rssi;}
  output[1] = '0'+((rssi/100)%10);
  output[2] = '0'+((rssi/10)%10);
  output[3] = '0'+ (rssi%10);
  TXString(output, (sizeof output)-1);
}

void ReadChannelsAndSendRSSI()
{
  uint8_t channel;
  int8_t rssi;
  
  for (channel=0;channel<255;channel++)
  {
    MRFI_RxIdle();
    mrfiSpiWriteReg(CHANNR,channel);
    MRFI_RxOn();
    rssi=MRFI_Rssi();
    print_rssi(rssi);
  }
}

void SetBaseFrequencyRegisters(uint8_t range)
{
    MRFI_RxIdle();
    
    //if Channel spacing = 25.390625 (min)
    switch(range)
    {
        // 2399.999634
      case 1:
        mrfiSpiWriteReg(FREQ0,0xC4);
        mrfiSpiWriteReg(FREQ1,0x4E);
        mrfiSpiWriteReg(FREQ2,0x5C);
        break;
        
        //needed 2406.474243
        //seted 2406.473846
      case 2:
        mrfiSpiWriteReg(FREQ0,0x83);
        mrfiSpiWriteReg(FREQ1,0x8E);
        mrfiSpiWriteReg(FREQ2,0x5C);
        break;
        
        //needed 2412.948456
        //seted 2412.948456
      case 3:
        mrfiSpiWriteReg(FREQ0,0x43);
        mrfiSpiWriteReg(FREQ1,0xCE);
        mrfiSpiWriteReg(FREQ2,0x5C);
        break;
        
        //needed 2419.423065
        //seted 2419.423065
      case 4:
        mrfiSpiWriteReg(FREQ0,0x03);
        mrfiSpiWriteReg(FREQ1,0x0E);
        mrfiSpiWriteReg(FREQ2,0x5D);
        break;
        
        //needed 2425.897675
        //seted 2425.897675
      case 5:
        mrfiSpiWriteReg(FREQ0,0xC3);
        mrfiSpiWriteReg(FREQ1,0x4D);
        mrfiSpiWriteReg(FREQ2,0x5D);
        break;

        //needed 2432.372284
        //seted 2432.372284
      case 6:
        mrfiSpiWriteReg(FREQ0,0x83);
        mrfiSpiWriteReg(FREQ1,0x8D);
        mrfiSpiWriteReg(FREQ2,0x5D);
        break;
        
        //needed 2438.846893
        //seted 2438.846893
      case 7:
        mrfiSpiWriteReg(FREQ0,0x43);
        mrfiSpiWriteReg(FREQ1,0xCD);
        mrfiSpiWriteReg(FREQ2,0x5D);
        break;
        
        //needed 2445.321503
        //seted 2445.321503
      case 8:
        mrfiSpiWriteReg(FREQ0,0x03);
        mrfiSpiWriteReg(FREQ1,0x0D);
        mrfiSpiWriteReg(FREQ2,0x5E);
        break;
        
        // 2399.999634
      default:
        mrfiSpiWriteReg(FREQ0,0xC4);
        mrfiSpiWriteReg(FREQ1,0x4E);
        mrfiSpiWriteReg(FREQ2,0x5C);
        break;
    }
}

int main(void)
{
  //int8_t rssi;
  //uint8_t channel;
  BSP_Init();
  MRFI_Init(/*0x9D, 0x58, 0x5C*/);
  P3SEL    |= 0x30;
  UCA0CTL1  = UCSSEL_2;
  UCA0BR0   = 0x41;
  UCA0BR1   = 0x3;
  UCA0MCTL  = UCBRS_2;
  UCA0CTL1 &= ~UCSWRST;
  MRFI_WakeUp();
  __bis_SR_register(GIE);
  while(1) {
    
    //change to 1th range of base frq
    SetBaseFrequencyRegisters(1);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(2);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(3);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(4);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(5);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(6);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(7);
    ReadChannelsAndSendRSSI();
    
    SetBaseFrequencyRegisters(8);
    ReadChannelsAndSendRSSI();
    
    TXString("\n",1);
  }
}

void MRFI_RxCompleteISR()
{
}
