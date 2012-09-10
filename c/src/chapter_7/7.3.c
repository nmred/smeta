#include <stdio.h>

int main(void)
{
	int value = 0;
	int *pvalue = NULL;
	
	pvalue = &value;
	
	printf("Input an integer:");
	scanf(" %d", pvalue);
	
	printf("\nYou entered %d\n", value);
	return 0;	
}
