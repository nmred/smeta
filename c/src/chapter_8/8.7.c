#include <stdio.h>

long *IncomePlus(long *pPay);

int main(void)
{
	long your_pay = 30000L;
	long *pold_pay = &your_pay;
	
	long *pnew_pay = NULL;
	pnew_pay = IncomePlus( pold_pay);
	printf("\nOld pay = $%ld", *pold_pay);	
	printf("\nNew pay = $%ld", *pnew_pay);	
	return 0;
}

long *IncomePlus(long *pPay)
{
	*pPay += 10000L;
	return pPay;
}
